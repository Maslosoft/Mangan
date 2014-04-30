<?php

/**
 * @author Piotr Maselkowski, Maslosoft
 * @copyright 2013 Maslosoft http://maslosoft.com
 * @license New BSD license
 * @version 2.0.1
 * @category ext
 * @package maslosoft/yii-mangan
 */

namespace Maslosoft\Mangan\Model;

use CMap;
use CUploadedFile;
use Exception;
use finfo;
use Maslosoft\Mangan\EmbeddedDocument;
use MongoGridFSFile;
use MongoId;
use Yii;

/**
 * Class for storing embedded files
 * @since 2.0.1
 * @author Piotr
 */
class File extends EmbeddedDocument
{

	/**
	 * @SafeValidator
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
		$this->setId(new MongoId);
		$this->_db = Yii::app()->mongodb->getDbInstance();
	}

	public function setOwner(EmbeddedDocument $owner)
	{
		parent::setOwner($owner);
		$root = $owner->getRoot();
		if ($root->hasEvent('onAfterDelete'))
		{
			$onAfterDelete = function($event)
			{
				$this->_onAfterDelete($event);
			};
			$onAfterDelete->bindTo($this);
			$root->onAfterDelete = $onAfterDelete;
		}
	}

	public function getId()
	{
		if (!$this->getAttribute('id'))
		{
			$this->setAttribute('id', new MongoId());
		}
		return $this->getAttribute('id');
	}

	public function setId($value)
	{
		if (!$value instanceof MongoId)
		{
			$value = new MongoId($value);
		}
		$this->setAttribute('id', $value);
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
		return $this->_db->getGridFS()->findOne(CMap::mergeArray($criteria, $params));
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
		Yii::app()->end();
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
		Yii::app()->end();
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
		$params = CMap::mergeArray($data, $params);

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
