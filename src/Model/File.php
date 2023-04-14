<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package   maslosoft/mangan
 * @licence   AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link      https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Model;

use finfo;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\EmbeddedDocument;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\File\Sender\Sender;
use Maslosoft\Mangan\File\Sender\Streamer;
use Maslosoft\Mangan\File\Wrappers\BucketWrapper;
use Maslosoft\Mangan\Helpers\IdHelper;
use Maslosoft\Mangan\Interfaces\File\WrapperInterface;
use Maslosoft\Mangan\Interfaces\FileInterface;
use Maslosoft\Mangan\Mangan;
use MongoDB\BSON\ObjectId as MongoId;
use MongoDB\Database;
use MongoDB\GridFS\Bucket;

/**
 * Class for storing embedded files, also stores some file information to
 * avoid querying GridFS
 * @since  2.0.1
 * @author Piotr
 */
class File extends EmbeddedDocument
{
	public const DefaultPrefix = 'fs';
	public const TmpPrefix = 'tmp';

	/**
	 * NOTE: This is also in GridFS, here is added to avoid querying GridFS just to get filename
	 * @var string
	 */
	public $filename = '';

	/**
	 * Size in bytes NOTE: @see $filename
	 * @var int
	 */
	public $size = 0;

	/**
	 * Root document class
	 * @var string Class name
	 */
	public $rootClass = '';

	/**
	 * Root document ID
	 * @var MongoId
	 */
	public $rootId = '';

	/**
	 * NOTE: Same not as for $filename field
	 * @var string
	 */
	public $contentType = '';

	/**
	 * Mongo DB instance
	 * @var Database
	 */
	private $_db = null;

	public function __construct($scenario = 'insert', $lang = '')
	{
		parent::__construct($scenario, $lang);
		$this->_id = new MongoId;
		$mangan = Mangan::fromModel($this);
		$this->_db = $mangan->getDbInstance();
	}

	public function setOwner(AnnotatedInterface $owner = null): void
	{
		parent::setOwner($owner);
		// TODO: Move to event handler class and attach fo File class or newly created interface
		$root = $this->getRoot();
		$onAfterDelete = function () {
			$this->_onAfterDelete();
		};
		$onAfterDelete->bindTo($this);
		Event::on($root, EntityManager::EventAfterDelete, $onAfterDelete);
	}

	public function getId(): MongoId
	{
		if (!$this->_id instanceof MongoId)
		{
			$this->_id = new MongoId($this->_id);
		}
		return $this->_id;
	}

	/**
	 * Get file from mongo grid
	 * @return ?WrapperInterface
	 */
	public function get(): ?WrapperInterface
	{
		return $this->_get();
	}

	/**
	 * Send file to browser
	 */
	public function send(): never
	{
		(new Sender)->send($this->_get());
	}

	/**
	 * Stream file to browser
	 */
	public function stream(): never
	{
		(new Streamer)->send($this->_get());
	}

	/**
	 * Set file data
	 * @param string|FileInterface $file
	 */
	public function set(FileInterface|string $file): void
	{
		if ($file instanceof FileInterface)
		{
			$tempName = $file->getTempName();
			$fileName = $file->getFileName();
		}
		else
		{
			$tempName = $file;
			$fileName = $file;
		}
		$this->_set($tempName, $fileName);
	}

	/**
	 * Get file with optional criteria params
	 * @param array $params
	 * @return WrapperInterface|null
	 */
	protected function _get(array $params = []): ?WrapperInterface
	{
		$criteria = [
			'parentId' => $this->_id,
			'isTemp' => false
		];
		$conditions = array_merge($criteria, $params);
		$target = $conditions['isTemp'] ? self::TmpPrefix : self::DefaultPrefix;
		$bucket = $this->select($target);
		$this->decorate($conditions);
		$result = $bucket->findOne($conditions);
		if ($result === null)
		{
			return null;
		}
		return new BucketWrapper($bucket, $result);
	}

	/**
	 * Set file with optional criteria params
	 * @param string  $tempName
	 * @param string  $fileName
	 * @param mixed[] $params
	 */
	protected function _set($tempName, $fileName, $params = []): void
	{
		$info = new finfo(FILEINFO_MIME);
		$mime = $info->file($tempName);
		/**
		 * TODO Check if root data is saved correctly
		 */
		if (!$this->getRoot()->_id instanceof MongoId)
		{
			// Assume string id
			if (IdHelper::isId($this->getRoot()->_id))
			{
				// Convert existing string id to MongoId
				$this->getRoot()->_id = new MongoId((string)$this->getRoot()->_id);
			}
			else
			{
				// Set new id now
				$this->getRoot()->_id = new MongoId;
			}
		}
		$rootId = $this->getRoot()->_id;
		$data = [
			'_id' => new MongoId(),
			'parentId' => $this->_id,
			'rootClass' => get_class($this->getRoot()),
			'rootId' => $rootId,
			'filename' => basename($fileName),
			'contentType' => $mime,
			'isTemp' => false
		];

		$this->filename = basename($fileName);
		$this->contentType = $mime;
		$this->size = filesize($tempName);
		$params = array_merge($data, $params);

		// Replace existing file, remove previous
		if (!$params['isTemp'])
		{
			$oldFiles = [
				'parentId' => $this->_id
			];
			// TODO: Update file, not delete
//			$this->select()->delete($oldFiles);
			// TODO: Find ids first, as the new API doesn't allow filtering
			// and remove temp files
			$this->deleteByCriteria(self::TmpPrefix, $oldFiles);
		}

		// Store new file
		$stream = fopen($tempName, 'rb');
		$target = $params['isTemp'] ? self::TmpPrefix : self::DefaultPrefix;
		// In main storage
		$options = [
			'metadata' => $params
		];
		$this->select($target)->uploadFromStream($fileName, $stream, $options);
	}

	/**
	 * This is fired after delete to remove chunks from GridFS
	 */
	protected function _onAfterDelete(): void
	{
		$criteria = [
			'parentId' => $this->_id
		];
		$this->deleteByCriteria(self::DefaultPrefix, $criteria);
		$this->deleteByCriteria(self::TmpPrefix, $criteria);
	}

	private function select(string $prefix = 'fs'): Bucket
	{
		$options = [
			'bucketName' => $prefix
		];
		return $this->_db->selectGridFSBucket($options);
	}

	private function deleteByCriteria(string $prefix, array $criteria): void
	{
		$this->decorate($criteria);
		$results = $this->select($prefix)->find($criteria);
		foreach ($results as $result)
		{
			$this->select($prefix)->delete($result['_id']);
		}
	}

	private function decorate(array &$criteria): void
	{
		$decorated = [];
		foreach($criteria as $name => $value)
		{
			$decorated["metadata.$name"] = $value;
		}
		$criteria = $decorated;
	}
}
