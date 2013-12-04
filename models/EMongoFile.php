<?php

/**
 * Class for storing embedded files
 *
 * @author Piotr
 */
class EMongoFile extends EMongoEmbeddedDocument
{

	/**
	 * @SafeValidator
	 * @var MongoId
	 */
	public $id = null;

	public $db = null;

	public function __construct($scenario = 'insert', $lang = '')
	{
		parent::__construct($scenario, $lang);
		$this->setId(new MongoId);
		$this->db = Yii::app()->mongodb->getDbInstance();
		
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
	 * Set file data
	 * @param CUploadedFile $file
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
		return $this->db->getGridFS()->findOne(CMap::mergeArray($criteria, $params));
	}

	/**
	 * Send file to the browser
	 * TODO Set proper content type
	 * @param MongoGridFSFile $file
	 */
	protected function _send(MongoGridFSFile $file)
	{
		header(sprintf('Content-Length: %d', $file->getSize()));
		header(sprintf('Content-Type: %s', 'image/jpeg'));

		// Cache it
		header('Pragma: public');
		header('Cache-Control: max-age=86400');
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
		echo $file->getBytes();
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

		$data = [
			'parentId' => $this->getId(),
			'filename' => $fileName,
			'contentType' => $mime,
			'isTemp' => false
		];

		$this->db->getGridFS()->put($tempName, CMap::mergeArray($data, $params));
	}

}
