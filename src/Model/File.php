<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Model;

use Exception;
use finfo;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\EmbeddedDocument;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Mangan;
use MongoGridFSFile;
use MongoId;

/**
 * Class for storing embedded files
 * @since 2.0.1
 * @author Piotr
 */
class File extends EmbeddedDocument
{

	/**
	 * @SafeValidator
	 * @Sanitizer('MongoObjectId')
	 * @var MongoId
	 */
	public $id = null;

	/**
	 * NOTE: This is also in gridfs, here is added to avoid querying gridfs just to get filename
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
	private $_db = null;

	public function __construct($scenario = 'insert', $lang = '')
	{
		parent::__construct($scenario, $lang);
		$this->id = new MongoId;
		$mangan = Mangan::fromModel($this);
		$this->_db = $mangan->getDbInstance();
	}

	public function setOwner(AnnotatedInterface $owner = null)
	{
		parent::setOwner($owner);
		$root = $this->getRoot();
		$onAfterDelete = function()
		{
			$this->_onAfterDelete();
		};
		$onAfterDelete->bindTo($this);
		Event::on($root, EntityManager::EventAfterDelete, $onAfterDelete);
	}

	public function getId()
	{
		if (!$this->id instanceof MongoId)
		{
			$this->id = new MongoId($this->id);
		}
		return $this->id;
	}

	/**
	 * Get file from mongo grid
	 * @return MongoGridFSFile
	 */
	public function get()
	{
		return $this->_get();
	}

	/**
	 * Send file to browser
	 */
	public function send()
	{
		$this->_send($this->_get());
	}

	/**
	 * Stream file to browser
	 */
	public function stream()
	{
		$this->_stream($this->_get());
	}

	/**
	 * Set file data
	 * @param CUploadedFile|string $file
	 */
	public function set($file)
	{
		if ($file instanceof CUploadedFile)
		{
			$tempName = $file->tempName;
			$fileName = $file->name;
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
	 * @param mixed[] $params
	 * @return MongoGridFSFile
	 */
	protected function _get($params = [])
	{
		$criteria = [
			'parentId' => $this->getId(),
			'isTemp' => false
		];
		return $this->_db->getGridFS()->findOne(array_merge($criteria, $params));
	}

	/**
	 * Send file to the browser
	 * @param MongoGridFSFile $file
	 */
	protected function _send(MongoGridFSFile $file = null)
	{
		if (null === $file)
		{
			throw new Exception('File not found');
		}
		$meta = (object) $file->file;
		header(sprintf('Content-Length: %d', $file->getSize()));
		header(sprintf('Content-Type: %s', $meta->contentType));
		header(sprintf('ETag: %s', $meta->md5));
		header(sprintf('Last-Modified: %s', gmdate('D, d M Y H:i:s \G\M\T', $meta->uploadDate->sec)));
		header(sprintf('Content-Disposition: filename="%s"', basename($meta->filename)));

		// Cache it
		header('Pragma: public');
		header('Cache-Control: max-age=86400');
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
		echo $file->getBytes();
		// Exit application after sending file
		exit;
	}

	protected function _stream(MongoGridFSFile $file)
	{
		$meta = (object) $file->file;
		if (ob_get_length())
		{
			ob_end_clean();
		}
		header(sprintf('Content-Length: %d', $file->getSize()));
		header(sprintf('Content-Type: %s', $meta->contentType));
		header(sprintf('ETag: %s', $meta->md5));
		header(sprintf('Last-Modified: %s', gmdate('D, d M Y H:i:s \G\M\T', $meta->uploadDate->sec)));
		header(sprintf('Content-Disposition: filename="%s"', basename($meta->filename)));
		// Cache it
		header('Pragma: public');
		header('Cache-Control: max-age=86400');
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
		$stream = $file->getResource();

		while (!feof($stream))
		{
			echo fread($stream, 8192);
			if (ob_get_length())
			{
				ob_flush();
			}
			flush();
		}
		// Exit application after stream completed
		exit;
	}

	/**
	 * Set file with optional criteria params
	 * @param string $tempName
	 * @param string $fileName
	 * @param mixed[] $params
	 */
	protected function _set($tempName, $fileName, $params = [])
	{
		$info = new finfo(FILEINFO_MIME);
		$mime = $info->file($tempName);
		/**
		 * TODO Check if root data is saved corectly
		 */
		$rootId = $this->getRoot()->id;
		$rootId = $rootId instanceof MongoId ? $rootId : new MongoId($rootId);
		$data = [
			'_id' => new MongoId(),
			'parentId' => $this->getId(),
			'rootClass' => $this->getRoot()->_class,
			'rootId' => $rootId,
			'filename' => $fileName,
			'contentType' => $mime,
			'isTemp' => false
		];

		$this->filename = $fileName;
		$this->contentType = $mime;
		$this->size = filesize($tempName);
		$params = array_merge($data, $params);

		// Replace existing file, remove previous
		if (!$params['isTemp'])
		{
			$oldFiles = [
				'parentId' => $this->getId()
			];
			$this->_db->getGridFS()->remove($oldFiles);
		}

		// Store new file
		$this->_db->getGridFS()->put($tempName, $params);
	}

	/**
	 * This is fired after delete to remove chunks from gridfs
	 */
	protected function _onAfterDelete()
	{
		$criteria = [
			'parentId' => $this->getId()
		];
		$this->_db->getGridFS()->remove($criteria);
	}

}
