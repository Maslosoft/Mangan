<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\EventDispatcher;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Helpers\CollectionNamer;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Interfaces\IEntityManager;
use Maslosoft\Mangan\Interfaces\IModel;
use Maslosoft\Mangan\Interfaces\IScenarios;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Options\EntityOptions;
use Maslosoft\Mangan\Signals\AfterDelete;
use Maslosoft\Mangan\Signals\AfterSave;
use Maslosoft\Mangan\Signals\BeforeDelete;
use Maslosoft\Mangan\Signals\BeforeSave;
use Maslosoft\Mangan\Transformers\FromDocument;
use Maslosoft\Signals\Signal;
use MongoCollection;
use MongoException;

/**
 * EntityManager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EntityManager implements IEntityManager
{

	/**
	 * Model
	 * @var IModel
	 */
	public $model = null;

	/**
	 *
	 * @var EventDispatcher
	 */
	public $ed = null;

	/**
	 *
	 * @var 
	 */
	public $meta = null;

	/**
	 * Options
	 * @var EntityOptions
	 */
	public $options = null;

	/**
	 * Current collection name
	 * @var string
	 */
	public $collectionName = '';

	/**
	 * Validator instance
	 * @var Validator
	 */
	private $validator = null;

	/**
	 * Current collection
	 * @var MongoCollection
	 */
	private $_collection = null;

	/**
	 * Model class name
	 * @var string
	 */
	private $_class = '';
	private $_db = null;

	/**
	 * Create entity manager
	 * @param IModel|object $model
	 * @throws ManganException
	 */
	public function __construct($model)
	{
		$this->model = $model;
		$this->_class = get_class($model);
		$this->options = new EntityOptions($model);
		$this->collectionName = CollectionNamer::nameCollection($model);
		$this->meta = ManganMeta::create($model);
		$this->validator = new Validator($model);
		$mangan = new Mangan($this->meta->type()->connectionId? : Mangan::DefaultConnectionId);
		if (!$this->collectionName)
		{
			throw new ManganException(sprintf('Invalid collection name for model: `%s`', $this->meta->type()->name));
		}
		$this->_collection = new MongoCollection($mangan->getDbInstance(), $this->collectionName);

		/*
		 * TODO Ensure indexes

		  if ($this->ensureIndexes && !isset(self::$_indexes[$this->getCollectionName()]))
		  {
		  $indexInfo = $this->getCollection()->getIndexInfo();
		  array_shift($indexInfo); // strip out default _id index

		  $indexes = [];
		  foreach ($indexInfo as $index)
		  {
		  $indexes[$index['name']] = [
		  'key' => $index['key'],
		  'unique' => isset($index['unique']) ? $index['unique'] : false,
		  ];
		  }
		  self::$_indexes[$this->getCollectionName()] = $indexes;

		  $this->ensureIndexes();
		  }
		 *
		  method ensureIndexes:
		  $indexNames = array_keys(self::$_indexes[$this->getCollectionName()]);
		  foreach ($this->indexes() as $name => $index)
		  {
		  if (!in_array($name, $indexNames))
		  {
		  $this->getCollection()->ensureIndex(
		  $index['key'], ['unique' => isset($index['unique']) ? $index['unique'] : false, 'name' => $name]
		  );
		  self::$_indexes[$this->getCollectionName()][$name] = $index;
		  }
		  }
		 *
		 */
	}

	/**
	 * Create model related entity manager.
	 * This will create customized entity manger if defined in model with EntityManager annotation.
	 * If no custom entity manager is defined this will return default EntityManager.
	 * @param IModel $model
	 * @return IEntityManager
	 */
	public static function create($model)
	{
		$emClass = ManganMeta::create($model)->type()->entityManager? : EntityManager::class;
		return new $emClass($model);
	}

	public function setAttributes($atributes)
	{
		/**
		 * TODO Set attributes from array
		 */
	}

	public function __set($name, $value)
	{
		;
	}

	public function insert($model = null)
	{
		$model = $model? : $this->model;
		if ($this->_beforeSave())
		{
			$rawData = FromDocument::toRawArray($model);

			$result = $this->_collection->insert($rawData, $this->options->getSaveOptions());

			// strict comparison needed
			if ($result !== false)
			{
				$this->_afterSave();
				return true;
			}
			throw new MongoException('Can\t save the document to disk, or attempting to save an empty document.');
		}
		return false;
	}

	/**
	 * Updates the row represented by this active record.
	 * All loaded attributes will be saved to the database.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * @param array $attributes list of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @param boolean modify if set true only selected attributes will be replaced, and not
	 * the whole document
	 * @return boolean whether the update is successful
	 * @throws MongoException if the record is new
	 * @throws MongoException on fail of update
	 * @throws MongoException on timeout of db operation , when safe flag is set to true
	 * @since v1.0
	 */
	public function update(array $attributes = null, $modify = false)
	{
		if ($this->getIsNewRecord())
		{
			throw new MongoException('The Document cannot be updated because it is new.');
		}
		if ($this->_beforeSave())
		{
			$rawData = FromDocument::toRawArray($this->model);

			// filter attributes if set in param
			if ($attributes !== null)
			{
				foreach ($rawData as $key => $value)
				{
					if (!in_array($key, $attributes))
					{
						unset($rawData[$key]);
					}
				}
			}
			if ($modify)
			{
				$criteria = PkManager::prepareFromModel($this->model);
				$result = $this->getCollection()->update($criteria->getConditions(), ['$set' => $rawData], $this->options->getSaveOptions(['multiple' => false]));
			}
			else
			{
				$result = $this->getCollection()->save($rawData, $this->options->getSaveOptions());
			}
			if ($result !== false)
			{ // strict comparison needed
				$this->_afterSave();
				return true;
			}
			throw new MongoException('Can\t save the document to disk, or attempting to save an empty document.');
		}
	}

	/**
	 * Atomic, in-place update method.
	 *
	 * @since v1.3.6
	 * @param Modifier $modifier updating rules to apply
	 * @param Criteria $criteria condition to limit updating rules
	 * @return boolean|mixed[]
	 */
	public function updateAll(Modifier $modifier, Criteria $criteria = null)
	{
		if ($modifier->canApply === true)
		{
			$this->applyScopes($criteria);
			$result = $this->getCollection()->update($criteria->getConditions(), $modifier->getModifiers(), $this->options->getSaveOptions([
						'upsert' => false,
						'multiple' => true
			]));
			return $result;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Saves the current record.
	 *
	 * The record is inserted as a row into the database table if its {@link isNewRecord}
	 * property is true (usually the case when the record is created using the 'new'
	 * operator). Otherwise, it will be used to update the corresponding row in the table
	 * (usually the case if the record is obtained using one of those 'find' methods.)
	 *
	 * Validation will be performed before saving the record. If the validation fails,
	 * the record will not be saved. You can call {@link getErrors()} to retrieve the
	 * validation errors.
	 *
	 * If the record is saved via insertion, its {@link isNewRecord} property will be
	 * set false, and its {@link scenario} property will be set to be 'update'.
	 * And if its primary key is auto-incremental and is not set before insertion,
	 * the primary key will be populated with the automatically generated key value.
	 *
	 * @param boolean $runValidation whether to perform validation before saving the record.
	 * If the validation fails, the record will not be saved to database.
	 * @param array $attributes list of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @return boolean whether the saving succeeds
	 * @since v1.0
	 */
	public function save($runValidation = true, $attributes = null)
	{
		if (!$runValidation || $this->validator->validate($attributes))
		{
			return $this->getIsNewRecord() ? $this->insert($attributes) : $this->update($attributes);
		}
		else
		{
			return false;
		}
	}

	/**
	 * Repopulates this active record with the latest data.
	 * @return boolean whether the row still exists in the database. If true, the latest data will be populated to this active record.
	 * @since v1.0
	 */
	public function refresh()
	{
		$conditions = PkManager::prepareFromModel($this->model)->getConditions();
		if (!$this->getIsNewRecord() && $this->getCollection()->count($conditions) == 1)
		{
			$this->setAttributes($this->getCollection()->find($conditions), false);
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Deletes the row corresponding to this Document.
	 * @return boolean whether the deletion is successful.
	 * @throws MongoException if the record is new
	 * @since v1.0
	 */
	public function delete()
	{
		if (!$this->getIsNewRecord())
		{
			if ($this->_beforeDelete())
			{
				$result = $this->deleteOne(PkManager::prepareFromModel($this->model));

				if ($result !== false)
				{
					$this->_afterDelete();
					$this->setIsNewRecord(true);
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			throw new MongoException('The Document cannot be deleted because it is new.');
		}
	}

	/**
	 * Deletes one document with the specified primary keys.
	 * <b>Does not raise beforeDelete</b>
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|Criteria $criteria query criteria.
	 * @since v1.0
	 */
	public function deleteOne($criteria = null)
	{
		$this->applyScopes($criteria);

		return $this->getCollection()->remove($criteria->getConditions(), $this->options->getSaveOptions([
							'justOne' => true
		]));
	}

	/**
	 * Deletes document with the specified primary key.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed $pkValue primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|Criteria $criteria query criteria.
	 * @since v1.0
	 */
	public function deleteByPk($pkValue, $criteria = null)
	{
		if ($this->_beforeDelete())
		{
			$this->applyScopes($criteria);
			$criteria->mergeWith(PkManager::prepare($this->model, $pkValue));

			return $this->getCollection()->remove($criteria->getConditions(), $this->options->getSaveOptions([
								'justOne' => true
			]));
		}
		return false;
	}

	/**
	 * Deletes documents with the specified primary keys.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|Criteria $criteria query criteria.
	 * @since v1.0
	 */
	public function deleteAll($criteria = null)
	{
		$this->applyScopes($criteria);

		return $this->getCollection()->remove($criteria->getConditions(), $this->options->getSaveOptions([
							'justOne' => false
		]));
	}

	public function getCollection()
	{
		return $this->_collection;
	}

	public function getIsNewRecord()
	{
		return true;
	}

	public function setIsNewRecord($new = true)
	{

	}

// <editor-fold defaultstate="collapsed" desc="Event handling">

	/**
	 * Take care of EventBeforeSave
	 * @see EventBeforeSave
	 * @return boolean
	 */
	private function _beforeSave()
	{
		$result = Event::Valid($this->model, IEntityManager::EventBeforeSave);
		if ($result)
		{
			(new Signal)->emit(new BeforeSave($this->model));
		}
		return $result;
	}

	/**
	 * Take care of EventAfterSave
	 * @see EventAfterSave
	 * @return boolean
	 */
	private function _afterSave()
	{
		Event::trigger($this->model, IEntityManager::EventAfterSave);
		(new Signal)->emit(new AfterSave($this->model));
		ScenarioManager::setScenario($this->model, IScenarios::Update);
	}

	/**
	 * This method is invoked before deleting a record.
	 * The default implementation raises the {@link onBeforeDelete} event.
	 * You may override this method to do any preparation work for record deletion.
	 * Make sure you call the parent implementation so that the event is raised properly.
	 * @return boolean whether the record should be deleted. Defaults to true.
	 * @since v1.0
	 */
	private function _beforeDelete()
	{
		$result = Event::valid($this->model, IEntityManager::EventBeforeDelete);
		if ($result)
		{
			(new Signal)->emit(new BeforeDelete($this->model));
			ScenarioManager::setScenario($this->model, IScenarios::Insert);
		}
		return $result;
	}

	/**
	 * This method is invoked after deleting a record.
	 * The default implementation raises the {@link onAfterDelete} event.
	 * You may override this method to do postprocessing after the record is deleted.
	 * Make sure you call the parent implementation so that the event is raised properly.
	 * @since v1.0
	 */
	private function _afterDelete()
	{
		Event::trigger($this->model, IEntityManager::EventAfterDelete, new ModelEvent($this->model));
		(new Signal)->emit(new AfterDelete($this->model));
	}

// </editor-fold>
}
