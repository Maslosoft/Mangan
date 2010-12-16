<?php
/**
 * EMongoGridFS.php
 *
 * PHP version 5.2+
 *
 * @author		Jose Martinez <jmartinez@ibitux.com>
 * @author		Philippe Gaultier <pgaultier@ibitux.com>
 * @copyright	2010 Ibitux
 * @license		http://www.yiiframework.com/license/ BSD license
 * @version		SVN: $Revision: $
 * @category	ext
 * @package		ext.YiiMongoDbSuite
 */

/**
 * EMongoGridFS
 *
 * Authorization management, dispatches actions and views on the system
 *
 * @author		Jose Martinez <jmartinez@ibitux.com>
 * @author		Philippe Gaultier <pgaultier@ibitux.com>
 * @copyright	2010 Ibitux
 * @license		http://www.yiiframework.com/license/ BSD license
 * @version		SVN: $Revision: $
 * @category	ext
 * @package		ext.YiiMongoDbSuite
 *
 */
abstract class EMongoGridFS extends EMongoDocument
{
	/**
	 * MongoGridFSFile will be stored here
	 * @var MongoGridFSFile
	 */
	private $_gridFSFile;

	/**
	 * Every EMongoGridFS object has to have one
	 * @var String $filename
	 */
	public $filename = null; // mandatory

	/**
	 * Indicates the temporary folder where we will save updated files to ensure we dont loose data
	 * @var string $_temporaryFolder
	 */
	private $_temporaryFolder = null;

	/**
	 * Returns current MongoCollection object
	 * By default this method use {@see getCollectionName()}
	 * @return MongoCollection
	 */
	public function getCollection()
	{
		if(!isset(self::$_collections[$this->getCollectionName()]))
			self::$_collections[$this->getCollectionName()] = $this->getDb()->getGridFS($this->getCollectionName());

		return self::$_collections[$this->getCollectionName()];
	}

	/**
	 * Set current MongoGridFSFile
	 * This setter is only used to populate model during factory init
	 * @param MongoGridFSFile $gridFs
	 * @return void
	 */
	public function setGridFsFile($gridFs)
	{
		//TODO:manage in another way gridFSFile, maybe attach this function as behavior
		$this->_gridFSFile = $gridFs;
	}

	/**
	 * Sets temporary folder, used for updates
	 * @param string $value
	 * @return void
	 */
	public function setTemporaryFolder($value)
	{
		$this->_temporaryFolder = $value;
	}

	/**
	 * Gets temporary folder, used for updates
	 * @return string
	 */
	public function getTemporaryFolder()
	{
		return $this->_temporaryFolder;
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
	 * @throws CException if the record is not new
	 */
	public function insert(array $attributes=null)
	{
		if(!$this->getIsNewRecord())
			throw new CDbException(Yii::t('yii','The EMongoDocument cannot be inserted to database because it is not new.'));
		if($this->beforeSave())
		{
			Yii::trace('Trace: '.__CLASS__.'::'.__FUNCTION__.'()', 'ext.MongoDb.EMongoGridFS');
			$rawData = $this->toArray();
			// free the '_id' container if empty, mongo will not populate it if exists
			if(empty($rawData['_id']))
				unset($rawData['_id']);
			// filter attributes if set in param
			if($attributes!==null)
			{
				foreach($rawData as $key=>$value)
				{
					if(!in_array($key, $attributes))
						unset($rawData[$key]);
				}
			}
			// check file
			$filename = "";
			if(!array_key_exists('filename', $rawData))
				throw new CException(Yii::t('yii', 'We need a filename'));
			else
			{
				$filename = $rawData['filename'];
				unset($rawData['filename']);
			}

			$result = $this->getCollection()->put($filename, $rawData);
			if($result !== false) // strict comparsion driver may return empty array
			{
				$this->_id = $result;
				//TODO: should be set in parent class
				$this->_gridFSFile = $this->getCollection()->findOne(array('_id'=>$this->_id));
				$this->setIsNewRecord(false);
				$this->setScenario('update');
				$this->afterSave();
				return true;
			}

			throw new CException(Yii::t('yii', 'Can\t save document to disk, or try to save empty document!'));
		}
		return false;
	}

	/**
	 * Insertion by Primary Key inserts a MongoGridFSFile forcing the MongoID
	 * @param MongoId $pk
	 * @param array $attributes
	 * @throws CDbException
	 * @throws CException
	 * @return boolean whether the insert success
	 */
	public function insertWithPk($pk, array $attributes=null) {
		if(!($pk instanceof MongoId))
			throw new CDbException(Yii::t('yii','The EMongoDocument cannot be inserted to database primary key is not defined.'));
		if($this->beforeSave())
		{
			Yii::trace('Trace: '.__CLASS__.'::'.__FUNCTION__.'()', 'ext.MongoDb.EMongoGridFS');
			$rawData = $this->toArray();
			$rawData['_id'] = $pk;

			// filter attributes if set in param
			if($attributes!==null)
			{
				foreach($rawData as $key=>$value)
				{
					if(!in_array($key, $attributes))
						unset($rawData[$key]);
				}
			}

			// check file
			$filename = "";
			if(!array_key_exists('filename', $rawData))
				throw new CException(Yii::t('yii', 'We need a filename'));
			else
			{
				$filename = $rawData['filename'];
				unset($rawData['filename']);
			}

			$result = $this->getCollection()->put($filename, $rawData);

			if($result !== false) // strict comparsion driver may return empty array
			{
				$this->_id = $result;
				//TODO: should be set in parent class
				$this->_gridFSFile = $this->getCollection()->findOne(array('_id'=>$this->_id));
				$this->setIsNewRecord(false);
				$this->setScenario('update');
				$this->afterSave();

				return true;
			}

			throw new CException(Yii::t('yii', 'Can\'t save document to disk, or try to save empty document!'));
		}
		return false;
	}

	/**
	 * Updates the row represented by this active record.
	 * All loaded attributes will be saved to the database.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * @param array $attributes list of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @return boolean whether the update is successful
	 * @throws CException if the record is new
	 */
	public function update(array $attributes=null)
	{
		Yii::trace('Trace: '.__CLASS__.'::'.__FUNCTION__.'()', 'ext.MongoDb.EMongoGridFS');
		if($this->getIsNewRecord())
			throw new CDbException(Yii::t('yii','The EMongoDocument cannot be updated because it is new.'));

		if($this->getTemporaryFolder() === null)
			Yii::trace('Trace: '.__CLASS__.'::'.__FUNCTION__.'() be careful, you are updating without safe mode, please enter your temporary folder', 'ext.MongoDb.EMongoGridFS');
		else
			$this->write($this->getTemporaryFolder().$this->getFilename());

		//keep old values
		$id = $this->_id;
		$filename = $this->getFilename();
		$gridFSFile = $this->_gridFSFile;

		if($this->deleteByPk($id) !== false)
		{
			$result =  $this->insertWithPk($this->_id, $attributes);
			if($result === true)
				return true;
			else
			{
				if($this->getTemporaryFolder() !== false)
				{
					//recover old file
					$this->_gridFSFile = $gridFSFile;
					$this->filename = $filename;
					$this->insertWithPk($id, $attributes);
				}
				else
					throw new CDbException(Yii::t('yii','Unable to update MongoGridFSFile.'));

				return false;
			}
		}
		else
			throw new CDbException(Yii::t('yii','Unable to delete old MongoGridFSFile.'));
		return false;
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
	 */
	public function populateRecord($document, $callAfterFind=true)
	{
		Yii::trace('Trace: '.__CLASS__.'::'.__FUNCTION__.'()', 'ext.MongoDb.EMongoGridFS');
		if($document instanceof MongoGridFSFile)
		{
			$model = parent::populateRecord($document->file, $callAfterFind);
			//TODO: this part of the populate record should be created in parent class to avoid public setter
			$model->setGridFsFile($document);
			return $model;
		}
		else
			return parent::populateRecord($document, $callAfterFind);
	}

	/**
	 * Returns the file size
	 * GetSize wrapper of MongoGridFSFile function
	 * @return integer file size
	 * False is returned if error
	 */
	public function getSize()
	{
		Yii::trace('Trace: '.__CLASS__.'::'.__FUNCTION__.'()', 'ext.MongoDb.EMongoGridFS');
		if(method_exists($this->_gridFSFile, 'getSize') === true)
			return $this->_gridFSFile->getSize();
		else
			return false;
	}

	/**
	 * Returns the filename
	 * GetFilename wrapper of MongoGridFSFile function
	 * @return string filename
	 * False is returned if error
	 */
	public function getFilename()
	{
		Yii::trace('Trace: '.__CLASS__.'::'.__FUNCTION__.'()', 'ext.MongoDb.EMongoGridFS');
		if (method_exists($this->_gridFSFile, 'getFilename') === true)
			return $this->_gridFSFile->getFilename();
		else
			return false;
	}

	/**
	 * Returns the file's contents as a string of bytes
	 * getBytes wrapper of MongoGridFSFile function
	 * @return string string of bytes
	 * False is returned if error
	 */
	public function getBytes()
	{
		Yii::trace('Trace: '.__CLASS__.'::'.__FUNCTION__.'()', 'ext.MongoDb.EMongoGridFS');
		if (method_exists($this->_gridFSFile, 'getBytes') === true)
			return $this->_gridFSFile->getBytes();
		else
			return false;
	}

	/**
	 * Writes this file to the system
	 * @param string $filename The location to which to write the file. If none is given, the stored filename will be used.
	 * @return integer number of bytes written
	 */
	public function write($filename=null)
	{
		Yii::trace('Trace: '.__CLASS__.'::'.__FUNCTION__.'()', 'ext.MongoDb.EMongoGridFS');
		if (method_exists($this->_gridFSFile, 'write') === true)
			return $this->_gridFSFile->write($filename);
		else
			return false;
	}

	/**
	 * Deletes documents with the specified primary keys.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed $pk primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|EMongoCriteria $condition query criteria.
	 * @return array whether the delete succeeds
	 */
	public function deleteAll($criteria=null)
	{
		Yii::trace('Trace: '.__CLASS__.'::'.__FUNCTION__.'()', 'ext.MongoDb.EMongoGridFS');
		$this->applyScopes($criteria);
		return $this->getCollection()->remove($criteria->getConditions(), array(
			'safe'=>$this->getMongoDBComponent()->safeFlag
		));
	}

	/**
	 * Deletes document with the specified primary key.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed $pk primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|EMongoCriteria $condition query criteria.
	 */
	public function deleteByPk($pk, $criteria=null)
	{
		if($this->beforeDelete())
		{
			Yii::trace('Trace: '.__CLASS__.'::'.__FUNCTION__.'()', 'ext.MongoDb.EMongoGridFS');
			$this->applyScopes($criteria);
			$criteria->_id('==', $pk);

			$result = $this->getCollection()->remove($criteria->getConditions(), array('justOne'=>true));
			$this->afterDelete();
			return $result;
		}
		return false;
	}

}