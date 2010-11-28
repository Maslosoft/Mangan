<?php

abstract class EMongoRecord extends EMongoEmbdedDocument
{
	/**
	 * MongoDB Connection object
	 *
	 * @var EMongoDbConnection $_db
	 */
	protected static $_db;

	private $_new=false;

	/**
	 * MongoDB special field, always visible
	 *
	 * @var mixed $_id
	 */
	public $_id;

	private static $_models=array();

	/**
	 * Returns (and in initial call sets) MongoDB Connection object
	 *
	 * @return MongoConnection
	 */
	public function getDb()
	{
		if(self::$_db===null)
			self::$_db = Yii::app()->getComponent('mongodb');
		return self::$_db;
	}

	/**
	 * Set MongoDB connection
	 *
	 * @param EMongoDbConnection $conn
	 */
	public function setDb(EMongoDbConnection $conn)
	{
		return self::$_db = $conn;
	}

	/**
	 * Returns primary index key name on a collection (default _id)
	 *
	 * @return mixed Key name, or array for composite keys
	 */
	public function primaryKey()
	{
		return '_id';
	}

	public function __construct($scenario='insert')
	{
		if($scenario===null)  // internally used by populateRecord() and model()
			return;

		$this->setScenario($scenario);
		$this->setIsNewRecord(true);

		$this->init();

		$this->attachBehaviors($this->behaviors());
		$this->afterConstruct();

		$this->initEmbdedDocuments();
	}

	/**
	 * Child implementation must return name of actual colection
	 *
	 * @return string
	 */
	abstract protected function getCollectionName();

	/**
	 * Returns MongoDB Collection object
	 *
	 * @return MongoCollection
	 */
	public function getCollection()
	{
		return $this->getDb()->db->{$this->getCollectionName()};
	}

	public function save($runValidation=true,$attributes=null)
	{
		if(!$runValidation || $this->validate($this->attributes))
			return $this->getIsNewRecord() ? $this->insert() : $this->update();
		else
			return false;
	}

	public function insert()
	{
		if(!$this->getIsNewRecord())
			throw new CDbException(Yii::t('yii','The active record cannot be inserted to database because it is not new.'));
		if($this->beforeSave())
		{
			$rawData=$this->toArray();
			if(empty($this->_id))
				unset($rawData['_id']);
			$this->getCollection()->insert($rawData, array('fsync'=>Yii::app()->getComponent('mongodb')->fsyncFlag));

			if(empty($rawData['_id']))
			{
				$this->addError('_id', "Can't save document to disk");
				return true;
			}
			else
			{
				$this->_id=$rawData['_id'];
				$this->setIsNewRecord(false);
				$this->afterSave();
				return true;
			}
		}
		else
			return false;
	}

	public function update()
	{
		if($this->getIsNewRecord())
			throw new CDbException(Yii::t('yii','The active record cannot be updated because it is new.'));
		if($this->beforeSave())
		{
			$this->getCollection()->save($this->toArray(), array('fsync'=>Yii::app()->getComponent('mongodb')->fsyncFlag));
			$this->afterSave();
		}
		else
			return false;
	}

	public function delete()
	{
		if(!$this->getIsNewRecord())
		{
			Yii::trace(get_class($this).'.delete()','system.db.ar.CActiveRecord');
			if($this->beforeDelete())
			{
				$result = $this->getCollection()->remove(array('_id'=>$this->_id), array('fsync'=>Yii::app()->getComponent('mongodb')->fsyncFlag, 'justOne'=>true));
				$this->afterDelete();
				$this->_id=null;
				$this->setIsNewRecord(true);
				return $result;
			}
			else
				return false;
		}
		else
			throw new CDbException(Yii::t('yii','The active record cannot be deleted because it is new.'));
	}

	public function refresh()
	{
		if(!$this->getIsNewRecord())
		{
			Yii::trace(get_class($this).'.refresh()','system.db.ar.CActiveRecord');
			$this->setAttributes($this->getCollection()->find(array('_id'=>$this->_id)), false);
		}
		else
			throw new CDbException(Yii::t('yii','The active record cannot be deleted because it is new.'));
	}

	public function count(array $query=null)
	{
		if($query!==null)
			return $this->getCollection()->count($query);
		else
			return $this->getCollection()->count();
	}

	public function countByAttributes(array $attributes)
	{
		return $this->count($attributes);
	}

	public function onBeforeSave($event)
	{
		$this->raiseEvent('onBeforeSave',$event);
	}

	public function onAfterSave($event)
	{
		$this->raiseEvent('onAfterSave',$event);
	}

	public function onBeforeDelete($event)
	{
		$this->raiseEvent('onBeforeDelete',$event);
	}

	public function onAfterDelete($event)
	{
		$this->raiseEvent('onAfterDelete',$event);
	}

	public function onBeforeFind($event)
	{
		$this->raiseEvent('onBeforeFind',$event);
	}

	public function onAfterFind($event)
	{
		$this->raiseEvent('onAfterFind',$event);
	}

	public function beforeSave()
	{
		if($this->hasEventHandler('onBeforeSave'))
		{
			$event=new CModelEvent($this);
			$this->onBeforeSave($event);
			return $event->isValid;
		}
		else
			return true;
	}

	protected function afterSave()
	{
		if($this->hasEventHandler('onAfterSave'))
			$this->onAfterSave(new CEvent($this));
	}

	protected function beforeDelete()
	{
		if($this->hasEventHandler('onBeforeDelete'))
		{
			$event=new CModelEvent($this);
			$this->onBeforeDelete($event);
			return $event->isValid;
		}
		else
			return true;
	}

	protected function afterDelete()
	{
		if($this->hasEventHandler('onAfterDelete'))
			$this->onAfterDelete(new CEvent($this));
	}

	protected function beforeFind()
	{
		if($this->hasEventHandler('onBeforeFind'))
			$this->onBeforeFind(new CEvent($this));
	}

	protected function afterFind()
	{
		if($this->hasEventHandler('onAfterFind'))
			$this->onAfterFind(new CEvent($this));
	}

	public function isNewRecord()
	{
		return $this->_new;
	}

	public function getIsNewRecord()
	{
		return $this->_new;
	}

	public function setIsNewRecord($value)
	{
		return $this->_new = ($value == true);
	}

	public function instantiate(array $document)
	{
		$class=get_class($this);
		$model=new $class(null);
		$model->initEmbdedDocuments();
		$model->setAttributes($document, false);
		return $model;
	}

	public function populateRecord($document, $callAfterFind=true)
	{
		if($document!==null)
		{
			$model=$this->instantiate($document);
			$model->setScenario('update');
			$model->init();

			$model->attachBehaviors($model->behaviors());

			if($callAfterFind)
				$model->afterFind();
			return $model;
		}
		else
			return null;
	}

	public function populateRecords($data, $callAfterFind=true)
	{
		$records=array();
		foreach($data as $record)
		{
			$records[] = $this->populateRecord($record, $callAfterFind);
		}
		return $records;
	}

	public function find(array $query=null)
	{
		if(!empty($query))
			$doc=$this->getCollection()->findOne($query);
		else
			$doc=$this->getCollection()->findOne();

		return $this->populateRecord($doc);
	}

	public function findAll(array $query=null, $sort=null, $limit=null, $offset=null)
	{
		if($query!==null)
			$cursor=$this->getCollection()->find($query);
		else
			$cursor=$this->getCollection()->find();

		if($limit!==null)
			$cursor->limit($limit);
		if($offset!==null)
			$cursor->skip($offset);
		if($sort!==null)
			$cursor->sort($sort);

		return $this->populateRecords($cursor);
	}

	public function findByPk($queryPk)
	{
		$pk=$this->primaryKey();
		$attributes=array();

		if(is_array($pk))
		{
			foreach($pk as $i=>$attributeName)
			{
				$attributes[$attributeName]=$queryPk[$i];
			}
		}
		else
			$attributes[$this->primaryKey()]=$queryPk;

		return $this->findByAttributes($attributes);
	}

	public function findByAttributes($attributes)
	{
		return $this->find($attributes);
	}

	public function findAllByAttributes($attributes, $sort=null, $limit=null, $offset=null)
	{
		return $this->findAll($attributes, $sort, $limit, $offset);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * The model returned is a static instance of the AR class.
	 * It is provided for invoking class-level methods (something similar to static class methods.)
	 *
	 * EVERY derived AR class must override this method as follows,
	 * <pre>
	 * public static function model($className=__CLASS__)
	 * {
	 *     return parent::model($className);
	 * }
	 * </pre>
	 *
	 * @param string $className active record class name.
	 * @return CActiveRecord active record model instance.
	 */
	public static function model($className=__CLASS__)
	{
		if(isset(self::$_models[$className]))
			return self::$_models[$className];
		else
		{
			$model=self::$_models[$className]=new $className(null);
			$model->attachBehaviors($model->behaviors());
			return $model;
		}
	}
}