<?php

/**
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @license New BSD license
 * @version 1.3
 * @category ext
 * @package ext.YiiMongoDbSuite
 */

/**
 * EMongoGridFS
 */
abstract class EMongoGridFS extends EMongoDocument
{
	/**
	 * Every EMongoGridFS object has to have one
	 * @var String $filename
	 * @since v1.3
	 */
	public $filename = null;
	/**
	 * MongoGridFSFile will be stored here
	 * @var MongoGridFSFile
	 */
	private $_gridFSFile;
	/**
	 * @var string Raw binary data. If set, will use this instead of file contents as specified by 'filename'.
	 */
	private $_bytes;

	/**
	 * Returns current MongoGridFS object
	 * By default this method use {@see getCollectionName()}
	 * @return MongoGridFS
	 */
	public function getCollection()
	{
		if (!isset(self::$_collections[$this->getCollectionName()]))
			self::$_collections[$this->getCollectionName()] = $this->getDb()->getGridFS($this->getCollectionName());

		return self::$_collections[$this->getCollectionName()];
	}

	/**
	 * Inserts a row into the table based on this active record attributes.
	 * If the table's primary key is auto-incremental and is null before insertion,
	 * it will be populated with the actual value after insertion.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * After the record is inserted to DB successfully, its {@link isNewRecord} property will be set false,
	 * and its {@link scenario} property will be set to be 'update'.
	 * @param array $attributes list of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @return boolean whether the attributes are valid and the record is inserted successfully.
	 * @throws EMongoException if the record is not new
	 * @throws EMongoException
	 * @since v1.3
	 */
	public function insert(array $attributes = null)
	{
		if (!$this->getIsNewRecord())
			throw new EMongoException(Yii::t('yii', 'The EMongoDocument cannot be inserted to the database because it is not new.'));

		if ($this->beforeSave())
		{
			Yii::trace('Trace: ' . __CLASS__ . '::' . __FUNCTION__ . '()', 'ext.MongoDb.EMongoGridFS');
			$rawData = $this->toArray();
			// free the '_id' container if empty, mongo will not populate it if exists
			if (empty($rawData['_id']))
				unset($rawData['_id']);

			return $this->store($rawData, $attributes);
		}
		return false;
	}

	/**
	 * Insertion by Primary Key inserts a MongoGridFSFile forcing the MongoID
	 * @param MongoId $pk
	 * @param array $attributes
	 * @throws EMongoException
	 * @throws EMongoException
	 * @return boolean whether the insert success
	 * @since v1.3
	 */
	public function insertWithPk($pk, array $attributes = null)
	{
		if (!($pk instanceof MongoId))
			throw new EMongoException(Yii::t('yii', 'The EMongoDocument cannot be inserted to the database beacuse its primary key is not defined.'));

		if ($this->beforeSave())
		{
			Yii::trace('Trace: ' . __CLASS__ . '::' . __FUNCTION__ . '()', 'ext.MongoDb.EMongoGridFS');
			$rawData = $this->toArray();
			$rawData['_id'] = $pk;

			return $this->store($rawData, $attributes);
		}
		return false;
	}

	/**
	 * Store a document.
	 * @param array $rawData
	 * @param array $attributes
	 * @return boolean True on success
	 * @throws EMongoException
	 */
	protected function store($rawData, $attributes)
	{
		// filter attributes if set in param
		if ($attributes !== null)
		{
			foreach ($rawData as $key => $value)
			{
				if (!in_array($key, $attributes))
					unset($rawData[$key]);
			}
		}

		if (!isset($rawData['filename']))
			throw new EMongoException(Yii::t('yii', 'A filename is required to save a GridFS document.'));

		// store bytes directly or store file
		if (isset($this->_bytes))
			$result = $this->getCollection()->storeBytes($this->_bytes, $rawData);
		else
		{
			$filename = $rawData['filename'];
			unset($rawData['filename']);
			$result = $this->getCollection()->storeFile($filename, $rawData);
		}

		 // strict compare because driver may return empty array
		if ($result !== false)
		{
			$this->_id = $result;
			//TODO: should be set in parent class
			$this->_gridFSFile = $this->getCollection()->findOne(array('_id' => $this->_id));
			$this->setIsNewRecord(false);
			$this->setScenario('update');
			$this->afterSave();
			return true;
		}
		throw new EMongoException(Yii::t('yii', 'Can\t save the document to disk, or attempting to save an empty document.'));
	}

	/**
	 * Updates the row represented by this active record.
	 * All loaded attributes will be saved to the database.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * @param array $attributes list of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @return boolean whether the update is successful
	 * @throws EMongoException if the record is new
	 * @since v1.3
	 */
	public function update(array $attributes = null)
	{
		Yii::trace('Trace: ' . __CLASS__ . '::' . __FUNCTION__ . '()', 'ext.MongoDb.EMongoGridFS');
		if ($this->getIsNewRecord())
			throw new EMongoException(Yii::t('yii', 'The EMongoDocument cannot be updated because it is new.'));

		if (is_file($this->filename) === true)
		{
			if ($this->deleteByPk($this->_id) !== false) {
				$result = $this->insertWithPk($this->_id, $attributes);
				if ($result === true)
					return true;
				else
					return false;
			}
		} else
			return parent::update($attributes, true);
	}

	/**
	 * Creates an EMongoGridFS with the given attributes.
	 * This method is internally used by the find methods.
	 * @param MongoGridFSFile $document mongo gridFSFile
	 * @param array $attributes attribute values (column name=>column value)
	 * @param boolean $callAfterFind whether to call {@link afterFind} after the record is populated.
	 * This parameter is added in version 1.0.3.
	 * @return EMongoDocument the newly created document. The class of the object is the same as the model class.
	 * Null is returned if the input data is false.
	 * @since v1.3
	 */
	public function populateRecord($document, $callAfterFind = true)
	{
		Yii::trace('Trace: ' . __CLASS__ . '::' . __FUNCTION__ . '()', 'ext.MongoDb.EMongoGridFS');
		if ($document instanceof MongoGridFSFile)
		{
			$model = parent::populateRecord($document->file, $callAfterFind);
			$model->_gridFSFile = $document;
			return $model;
		}
		else
			return parent::populateRecord($document, $callAfterFind);
	}

	/**
	 * Set raw bytes. If set, will use this instead of file contents as specified by 'filename'.
	 * @param string $bytes
	 */
	public function setBytes($bytes)
	{
		$this->_bytes = $bytes;
	}

	/**
	 * Returns the file size
	 * GetSize wrapper of MongoGridFSFile function
	 * @return integer file size or False on error.
	 * @since v1.3
	 */
	public function getSize()
	{
		Yii::trace('Trace: ' . __CLASS__ . '::' . __FUNCTION__ . '()', 'ext.MongoDb.EMongoGridFS');
		if (method_exists($this->_gridFSFile, 'getSize') === true)
			return $this->_gridFSFile->getSize();
		else
			return false;
	}

	/**
	 * Returns the filename
	 * GetFilename wrapper of MongoGridFSFile function
	 * @return string filename or False on error.
	 * @since v1.3
	 */
	public function getFilename()
	{
		Yii::trace('Trace: ' . __CLASS__ . '::' . __FUNCTION__ . '()', 'ext.MongoDb.EMongoGridFS');
		if (method_exists($this->_gridFSFile, 'getFilename') === true)
			return $this->_gridFSFile->getFilename();
		else
			return false;
	}

	/**
	 * Returns the file's contents as a string of bytes.
	 * @return mixed string of bytes or False on error.
	 * @since v1.3
	 */
	public function getBytes()
	{
		Yii::trace('Trace: ' . __CLASS__ . '::' . __FUNCTION__ . '()', 'ext.MongoDb.EMongoGridFS');
		if (method_exists($this->_gridFSFile, 'getBytes') === true)
			return $this->_gridFSFile->getBytes();
		else if (isset($this->_bytes))
			return $this->_bytes;
		else
			return false;
	}

	/**
	 * Writes the file to the system.
	 * @param string $filename The location to which to write the file.
	 * If none is given, the stored filename will be used.
	 * @return integer number of bytes written or False on error.
	 * @since v1.3
	 */
	public function write($filename = null)
	{
		Yii::trace('Trace: ' . __CLASS__ . '::' . __FUNCTION__ . '()', 'ext.MongoDb.EMongoGridFS');
		if (method_exists($this->_gridFSFile, 'write') === true)
			return $this->_gridFSFile->write($filename);
		else
			return false;
	}
}
