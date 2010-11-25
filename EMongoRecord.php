<?php

abstract class EMongoRecord extends CModel
{
	public static $db=null;
	//private $_id;
	private $_new = false;

	protected $_document;

	private static $_models=array();

	public function getDb()
	{
		if(self::$db===null)
			self::$db = Yii::app()->getComponent('mongodb')->db;
		return self::$db;
	}

	public function setDb(EMongoDbConnection $db)
	{
		self::$db = $db;
	}

	public function getAttributes($names=true)
	{
		$doc=$this->_document;
		if(is_array($names))
		{
			$attrs=array();
			foreach($names as $name)
			{
				if(property_exists($this,$name))
					$attrs[$name]=$this->$name;
				else
					$attrs[$name]=isset($doc[$name])?$doc[$name]:null;
			}
			return $attrs;
		}
		else
			return $doc;
	}

	public function init(){}

	public function __construct($scenario='insert')
	{
		if($scenario===null)  // internally used by populateRecord() and model()
			return;

		$this->setScenario($scenario);
		$this->setIsNewRecord(true);

		$this->_document = new EMongoDocument($this->defaultSchema());

		$this->init();

		$this->attachBehaviors($this->behaviors());
		$this->afterConstruct();
	}

	public function __get($name)
	{
		if(isset($this->_document->$name))
			return $this->_document->$name;
		// CComponent throws exeption if __get method not find anything,
		// in this case we can check if schema allows dynamic creation and
		// contiune work
		try
		{
			return parent::__get($name);
		}
		catch(Exeption $e)
		{
			// no getter/behavior event found, check if document allows dynamic creation
			if(!$this->_document->isBlockedSchema())
				return $this->_document->$name;
			else
				// document is blocked, forward exception futher
				throw $e;
		}
	}

	public function __set($name, $value)
	{
		if(isset($this->_document->$name))
			return $this->_document->$name = $value;
		try
		{
			return parent::__set($name, $value);
		}
		catch(Exception $e)
		{
			if(!$this->_document->isBlockedSchema())
				return $this->_document->$name=$value;
			else
				throw $e;
		}
	}

	abstract protected function getCollectionName();

	public function getCollection()
	{
		return $this->db->{$this->getCollectionName()};
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
		if($this->beforeSave())
		{
			$rawDoc = $this->_document->toArray();
			$this->collection->insert($rawDoc, array('fsync'=>true));

			if(empty($rawDoc['_id']))
			{
				$this->addError('_id', "Can't save document to disk");
				return true;
			}
			else
			{
				$this->_document=new EMongoDocument($rawDoc);
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
		if($this->beforeSave())
		{
			$this->collection->save($this->_document, array('fsync'=>true));
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
				$result=$this->deleteByPk($this->getPrimaryKey())>0;
				$this->afterDelete();
				return $result;
			}
			else
				return false;
		}
		else
			throw new CDbException(Yii::t('yii','The mongo record cannot be deleted because it is new.'));
	}

	public function refresh()
	{
		// TODO!!!
	}

	public function saveAttributes($attributes=array())
	{
		// FIXME
		$this->_document=array_merge($this->_document,$attributes);
	}

	public function getIsNewRecord()
	{
		return $this->_new;
	}

	public function setIsNewRecord($value)
	{
		return $this->_new = ($value == true);
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

	protected function beforeSave()
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

	protected function afterConstruct()
	{
		if($this->hasEventHandler('onAfterConstruct'))
			$this->onAfterConstruct(new CEvent($this));
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

	protected function instantiate($document)
	{
		$class=get_class($this);
		$model=new $class(null);
		$model->_document=new EMongoDocument($document);
		return $model;
	}

	public function find($query=array())
	{
		if(!empty($query))
			$doc=$this->collection->findOne($query);
		else
			$doc=$this->collection->findOne();

		return $this->populateRecord($doc);
	}

	public function findAll($criteria=array())
	{
		if(isset($criteria['query']))
			$docs=$this->collection->find($criteria['query']);
		else
			$docs=$this->collection->find();

		if(isset($criteria['limit']))
			$docs=$docs->limit($criteria['limit']);
		if(isset($criteria['offset']))
			$docs=$docs->skip($criteria['offset']);
		if(isset($criteria['sort']))
			$docs=$docs->sort($criteria['sort']);
		return $this->populateRecords($docs);
	}

	public function populateRecord($document, $callAfterFind=true)
	{
		if($document!==null)
		{
			$record=$this->instantiate($document);
			$record->setScenario('update');
			$record->init();

			$record->attachBehaviors($record->behaviors());

			if($callAfterFind)
				$record->afterFind();
			return $record;
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

	public function defaultSchema()
	{
		return array();
	}

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