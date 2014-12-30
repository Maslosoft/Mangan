<?php

/**
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @license New BSD license
 * @version 2.0.1
 * @category ext
 * @package ext.YiiMongoDbSuite
 */

namespace Maslosoft\Mangan;

use CLogger;
use Exception;
use Maslosoft\Mangan\Core\Component;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Signals\AfterDelete;
use Maslosoft\Mangan\Signals\AfterSave;
use Maslosoft\Signals\Signal;
use MongoCollection;
use MongoCursor;
use MongoDB;
use MongoException;
use MongoId;
use MongoRegex;
use Yii;

/**
 * Document
 *
 * @property-read MongoDB $db
 * @since v1.0
 */
abstract class Document extends EmbeddedDocument
{

	/**
	 * Mongo id field
	 * @KoBindable(false)
	 * @see setId()
	 * @see getId()
	 * @var MongoId|mixed
	 */
	public $_id;

	/**
	 * Alias to _id
	 * @Label('Database ID')
	 * @Persistent(false)
	 * @see setId()
	 * @see getId()
	 * @var string
	 */
	public $id;

	/**
	 * Entity manager
	 * @var EntityManager
	 */
	public $em = null;

	/**
	 * Finder
	 * @var Finder
	 */
	private $finder = null;
	private $_new = false;  // whether this instance is new or not
	private $_criteria = null; // query criteria (used by finder only)

	/**
	 * Static array that holds mongo collection object instances,
	 * protected access since v1.3
	 * @var array $_collections static array of loaded collection objects
	 * @since v1.3
	 */
	protected static $_collections = [];  // MongoCollection object
	private static $_models = [];
	private static $_indexes = [];  // Hold collection indexes array
	private $_fsyncFlag = null; // Object level FSync flag
	private $_safeFlag = null; // Object level Safe flag
	protected $useCursor = null; // Whatever to return cursor instead on raw array

	/**
	 * @var boolean $ensureIndexes whatever to check and create non existing indexes of collection
	 * @since v1.1
	 */
	protected $ensureIndexes = true; // Whatever to ensure indexes

	/**
	 * MongoDB component static instance.
	 * @var MongoDB $_emongoDb;
	 * @since v1.0
	 */
	protected static $_emongoDb;

	/**
	 * Add scopes functionality.
	 * @see Component::__call()
	 * @since v1.0
	 */
	public function __call($name, $parameters)
	{
		$scopes = $this->scopes();
		if (isset($scopes[$name]))
		{
			$this->getDbCriteria()->mergeWith($scopes[$name]);
			return $this;
		}
		return parent::__call($name, $parameters);
	}

	/**
	 * Constructor {@see setScenario()}.
	 * @param string $scenario
	 * @since v1.0
	 */
	public function __construct($scenario = 'insert', $lang = '')
	{
		$this->_key = (string) new MongoId();
		$this->_class = get_class($this);
		$this->meta = ManganMeta::create($this);
		$this->meta->initModel($this);
		$this->em = new EntityManager($this);
		$this->finder = new Finder($this->em);
		$this->setLang($lang);

		// internally used by populateRecord() and model()
		if ($scenario == null)
		{
			return;
		}

		$this->setScenario($scenario);
		$this->setIsNewRecord(true);

		$this->init();

		$this->attachBehaviors($this->behaviors());
		$this->afterConstruct();

		$this->initEmbeddedDocuments();
	}

	public function getId()
	{
		return (string) $this->_id;
	}

	public function setId($value)
	{
		if (!$value instanceof MongoId)
		{
			$value = new MongoId($value);
		}
		$this->_id = $value;
	}

	/**
	 * This method must return collection name for use with this model
	 * this must be implemented in child classes
	 *
	 * this is read-only defined only at class define
	 * if you want to set different collection during run-time
	 * use {@see setCollection()}.
	 * @return string collection name
	 * @since v1.0
	 */
	public function getCollectionName()
	{
		return str_replace('\\', '.', $this->_class);
	}

	/**
	 * This method determines if collection can store different types of documents.<br />
	 * If it returns FALSE object type <em>might</em> depend on `_class` attribute value.<br />
	 * It it returns TRUE object type will be set to current model instance type
	 */
	public function isCollectionHomogenous()
	{
		return true;
	}

	/**
	 * Returns the mongo criteria associated with this model.
	 * @param boolean $createIfNull whether to create a criteria instance if it does not exist. Defaults to true.
	 * @return Criteria the query criteria that is associated with this model.
	 * This criteria is mainly used by {@link scopes named scope} feature to accumulate
	 * different criteria specifications.
	 * @since v1.0
	 */
	public function getDbCriteria($createIfNull = true)
	{
		if ($this->_criteria === null)
		{
			if (($c = $this->defaultScope()) !== [] || $createIfNull)
			{
				$this->_criteria = new Criteria($c);
			}
		}
		return $this->_criteria;
	}

	/**
	 * Set girrent object, this will override previous criteria
	 *
	 * @param Criteria $criteria
	 * @since v1.0
	 */
	public function setDbCriteria($criteria)
	{
		if (is_array($criteria))
		{
			$this->_criteria = new Criteria($criteria);
		}
		else if ($criteria instanceof Criteria)
		{
			$this->_criteria = $criteria;
		}
		else
		{
			$this->_criteria = new Criteria();
		}
	}

	/**
	 * Returns the declaration of named scopes.
	 * A named scope represents a query criteria that can be chained together with
	 * other named scopes and applied to a query. This method should be overridden
	 * by child classes to declare named scopes for the particular document classes.
	 * For example, the following code declares two named scopes: 'recently' and
	 * 'published'.
	 * <pre>
	 * return array(
	 * 	'published'=>array(
	 * 		'conditions'=>array(
	 * 				'status'=>array('==', 1),
	 * 		),
	 * 	),
	 * 	'recently'=>array(
	 * 		'sort'=>array('create_time'=>Criteria::SORT_DESC),
	 * 		'limit'=>5,
	 * 	),
	 * );
	 * </pre>
	 * If the above scopes are declared in a 'Post' model, we can perform the following
	 * queries:
	 * <pre>
	 * $posts=Post::model()->published()->findAll();
	 * $posts=Post::model()->published()->recently()->findAll();
	 * $posts=Post::model()->published()->published()->recently()->find();
	 * </pre>
	 *
	 * @return array the scope definition. The array keys are scope names; the array
	 * values are the corresponding scope definitions. Each scope definition is represented
	 * as an array whose keys must be properties of {@link Criteria}.
	 * @since v1.0
	 */
	public function scopes()
	{
		return [];
	}

	/**
	 * Returns the default named scope that should be implicitly applied to all queries for this model.
	 * Note, default scope only applies to SELECT queries. It is ignored for INSERT, UPDATE and DELETE queries.
	 * The default implementation simply returns an empty array. You may override this method
	 * if the model needs to be queried with some default criteria (e.g. only active records should be returned).
	 * @return array the mongo criteria. This will be used as the parameter to the constructor
	 * of {@link Criteria}.
	 * @since v1.2.2
	 */
	public function defaultScope()
	{
		return [];
	}

	/**
	 * Resets all scopes and criteria applied including default scope.
	 *
	 * @return Document
	 * @since v1.0
	 */
	public function resetScope()
	{
		$this->_criteria = new Criteria();
		return $this;
	}

	/**
	 * Applies the query scopes to the given criteria.
	 * This method merges {@link dbCriteria} with the given criteria parameter.
	 * It then resets {@link dbCriteria} to be null.
	 * @param Criteria|array $criteria the query criteria. This parameter may be modified by merging {@link dbCriteria}.
	 * @since v1.2.2
	 */
	public function applyScopes(&$criteria)
	{
		if ($criteria === null)
		{
			$criteria = new Criteria();
		}
		elseif (is_array($criteria))
		{
			$criteria = new Criteria($criteria);
		}
		elseif (!($criteria instanceof Criteria))
		{
			throw new MongoException('Cannot apply scopes to criteria');
		}
		if (($c = $this->getDbCriteria(false)) !== null)
		{
			$c->mergeWith($criteria);
			$criteria = $c;
			$this->_criteria = null;
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
		return $this->em->save($runValidation, $attributes);
	}

	/**
	 * Inserts a row into the table based on this active record attributes.
	 * If the table's primary key is auto-incremental and is null before insertion,
	 * it will be populated with the actual value after insertion.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * After the record is inserted to DB successfully, its {@link isNewRecord} property will be set false,
	 * and its {@link scenario} property will be set to be 'update'.
	 * @return boolean whether the attributes are valid and the record is inserted successfully.
	 * @throws MongoException if the record is not new
	 * @throws MongoException on fail of insert or insert of empty document
	 * @throws MongoException on fail of insert, when safe flag is set to true
	 * @throws MongoException on timeout of db operation , when safe flag is set to true
	 * @since v1.0
	 */
	public function insert()
	{
		return $this->em->insert();
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
		return $this->em->update($attributes, $modify);
	}

	/**
	 * Atomic, in-place update method.
	 *
	 * @since v1.3.6
	 * @param Modifier $modifier updating rules to apply
	 * @param Criteria $criteria condition to limit updating rules
	 * @return boolean
	 */
	public function updateAll(Modifier $modifier, Criteria $criteria = null)
	{
		return $this->em->updateAll($modifier, $criteria);
	}

	/**
	 * Deletes the row corresponding to this Document.
	 * @return boolean whether the deletion is successful.
	 * @throws MongoException if the record is new
	 * @since v1.0
	 */
	public function delete()
	{
		return $this->em->delete();
	}

	/**
	 * Deletes document with the specified primary key.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed $pk primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|Criteria $criteria query criteria.
	 * @since v1.0
	 */
	public function deleteByPk($pk, $criteria = null)
	{
		$this->em->deleteByPk($pk, $criteria);
	}

	/**
	 * Repopulates this active record with the latest data.
	 * @return boolean whether the row still exists in the database. If true, the latest data will be populated to this active record.
	 * @since v1.0
	 */
	public function refresh()
	{
		$this->em->refresh();
	}

	/**
	 * Finds a single Document with the specified condition.
	 * @param array|Criteria $criteria query criteria.
	 *
	 * If an array, it is treated as the initial values for constructing a {@link Criteria} object;
	 * Otherwise, it should be an instance of {@link Criteria}.
	 *
	 * @return Document the record found. Null if no record is found.
	 * @since v1.0
	 */
	public function find($criteria = null)
	{
		$this->finder->find($criteria);
	}

	/**
	 * Finds all documents satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|Criteria $criteria query criteria.
	 * @return array list of documents satisfying the specified condition. An empty array is returned if none is found.
	 * @since v1.0
	 */
	public function findAll($criteria = null)
	{
		return $this->finder->findAll($criteria);
	}

	/**
	 * Finds document with the specified primary key.
	 * In MongoDB world every document has '_id' unique field, so with this method that
	 * field is in use as PK!
	 * See {@link find()} for detailed explanation about $condition.
	 * @param mixed $pk primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|Criteria $criteria query criteria.
	 * @return the document found. An null is returned if none is found.
	 * @since v1.0
	 */
	public function findByPk($pk, $criteria = null)
	{
		return $this->finder->findByPk($pk, $criteria);
	}

	/**
	 * Finds all documents with the specified primary keys.
	 * In MongoDB world every document has '_id' unique field, so with this method that
	 * field is in use as PK by default.
	 * See {@link find()} for detailed explanation about $condition.
	 * @param mixed $pk primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|Criteria $criteria query criteria.
	 * @return Document[]|Cursor - Array or cursor of Documents
	 * @since v1.0
	 */
	public function findAllByPk($pk, $criteria = null)
	{
		return $this->finder->findAllByPk($pk, $criteria);
	}

	/**
	 * Finds document with the specified attributes.
	 *
	 * See {@link find()} for detailed explanation about $condition.
	 * @param mixed[] Array of stributes and values in form of ['attributeName' => 'value']
	 * @return Document - the document found. An null is returned if none is found.
	 * @since v1.0
	 */
	public function findByAttributes(array $attributes)
	{
		return $this->finder->findByAttributes($attributes);
	}

	/**
	 * Finds all documents with the specified attributes.
	 *
	 * @param mixed[] Array of stributes and values in form of ['attributeName' => 'value']
	 * @return Document[]|Cursor - Array or cursor of Documents
	 * @since v1.0
	 */
	public function findAllByAttributes(array $attributes)
	{
		return $this->finder->findAllByAttributes($attributes);
	}

	/**
	 * Counts all documents satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|Criteria $criteria query criteria.
	 * @return integer Count of all documents satisfying the specified condition.
	 * @since v1.0
	 */
	public function count($criteria = null)
	{
		$this->applyScopes($criteria);
		return $this->getCollection()->count($criteria->getConditions());
	}

	/**
	 * Counts all documents satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed[] Array of stributes and values in form of ['attributeName' => 'value']
	 * @return integer Count of all documents satisfying the specified condition.
	 * @since v1.2.2
	 */
	public function countByAttributes(array $attributes)
	{
		$criteria = new Criteria;
		foreach ($attributes as $name => $value)
		{
			$criteria->$name = $value;
		}

		$this->applyScopes($criteria);

		return $this->getCollection()->count($criteria->getConditions());
	}

	/**
	 * Checks whether there is row satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed $condition query condition or criteria.
	 * @param array $params parameters to be bound to an SQL statement.
	 * @return boolean whether there is row satisfying the specified condition.
	 */
	public function exists(Criteria $criteria)
	{
		return $this->count($criteria) > 0;
	}

	/**
	 * Deletes documents with the specified primary keys.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed $pk primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|Criteria $condition query criteria.
	 * @since v1.0
	 */
	public function deleteAll($criteria = null)
	{
		$this->em->deleteAll($criteria);
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
		$this->em->deleteOne($criteria);
	}

	/**
	 * This method is invoked before an AR finder executes a find call.
	 * The find calls include {@link find}, {@link findAll}, {@link findByPk},
	 * {@link findAllByPk}, {@link findByAttributes} and {@link findAllByAttributes}.
	 * The default implementation raises the {@link onBeforeFind} event.
	 * If you override this method, make sure you call the parent implementation
	 * so that the event is raised properly.
	 *
	 * Starting from version 1.1.5, this method may be called with a hidden {@link CDbCriteria}
	 * parameter which represents the current query criteria as passed to a find method of AR.
	 * @since v1.0
	 */
	protected function beforeFind()
	{
		if ($this->hasEventHandler('onBeforeFind'))
		{
			$event = new ModelEvent($this);
			$this->onBeforeFind($event);
			return $event->isValid;
		}
		else
		{
			return true;
		}
	}

	/**
	 * This method is invoked after each record is instantiated by a find method.
	 * The default implementation raises the {@link onAfterFind} event.
	 * You may override this method to do postprocessing after each newly found record is instantiated.
	 * Make sure you call the parent implementation so that the event is raised properly.
	 * @since v1.0
	 */
	protected function afterFind()
	{
		if ($this->hasEventHandler('onAfterFind'))
		{
			$this->onAfterFind(new ModelEvent($this));
		}
	}

	/**
	 * Creates an document instance.
	 * This method is called by {@link populateRecord} and {@link populateRecords}.
	 * You may override this method if the instance being created
	 * depends the attributes that are to be populated to the record.
	 * @param array $attributes list of attribute values for the active records.
	 * @return Document the document
	 * @since v1.0
	 */
	protected function instantiate($attributes)
	{
		if ($this->isCollectionHomogenous())
		{
			$class = $this->_class;
		}
		else
		{
			$class = isset($attributes['_class']) ? $attributes['_class'] : $this->_class;
		}

		// This is to avoid ovverwriting _class property
		unset($attributes['_class']);

		$model = new $class(null, $this->getLang());
		$model->initEmbeddedDocuments();
		foreach ($model->meta->fields() as $field => $value)
		{
			if (isset($attributes[$field]))
			{
				if ($model->meta->$field->i18n)
				{
					if (!is_array($attributes[$field]))
					{
						$attributes[$field] = [];
					}

					foreach (Yii::app()->languages as $lang => $langName)
					{
						if (isset($attributes[$field][$lang]))
						{
							$model->setAttribute($field, $attributes[$field][$lang], $lang);
						}
						else
						{
							$model->setAttribute($field, $model->meta->$field->default, $lang);
						}
					}
				}
				else
				{
					$model->setAttribute($field, $attributes[$field]);
				}
			}
		}
		return $model;
	}

	/**
	 * Creates an Document with the given attributes.
	 * This method is internally used by the find methods.
	 * @param array $document attribute values (column name=>column value)
	 * @param boolean $callAfterFind whether to call {@link afterFind} after the record is populated.
	 * This parameter is added in version 1.0.3.
	 * @return Document the newly created document. The class of the object is the same as the model class.
	 * Null is returned if the input data is false.
	 * @since v1.0
	 */
	public function populateRecord($document, $callAfterFind = true)
	{
		if ($document !== null)
		{
			$model = $this->instantiate($document);
			$model->setScenario('update');
			$model->init();

			$model->attachBehaviors($model->behaviors());

			if ($callAfterFind)
			{
				$model->afterFind();
			}
			return $model;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Creates a list of documents based on the input data.
	 * This method is internally used by the find methods.
	 * @param MongoCursor $cursor Results found to populate active records.
	 * @param boolean $callAfterFind whether to call {@link afterFind} after each record is populated.
	 * This parameter is added in version 1.0.3.
	 * @param string $index the name of the attribute whose value will be used as indexes of the query result array.
	 * If null, it means the array will be indexed by zero-based integers.
	 * @return array list of active records.
	 * @since v1.0
	 */
	public function populateRecords($cursor, $callAfterFind = true, $index = null)
	{
		$records = [];
		foreach ($cursor as $attributes)
		{
			if (($record = $this->populateRecord($attributes, $callAfterFind)) !== null)
			{
				if ($index === null)
				{
					$records[] = $record;
				}
				else
				{
					$records[$record->$index] = $record;
				}
			}
		}
		return $records;
	}

	/**
	 * Magic search method, provides basic search functionality.
	 *
	 * Returns Document object ($this) with criteria set to
	 * regexp: /$attributeValue/i
	 * used for Data provider search functionality
	 * @param boolean $caseSensitive whatever do a case-sensitive search, default to false
	 * @return Document
	 * @since v1.2.2
	 */
	public function search($caseSensitive = false)
	{
		$criteria = $this->getDbCriteria();

		foreach ($this->getSafeAttributeNames() as $attribute)
		{
			if ($this->$attribute !== null && $this->$attribute !== '')
			{
				if (is_array($this->$attribute) || is_object($this->$attribute))
				{
					$criteria->$attribute = $this->$attribute;
				}
				else if (preg_match('/^(?:\s*(<>|<=|>=|<|>|=|!=|==))?(.*)$/', $this->$attribute, $matches))
				{
					$op = $matches[1];
					$value = $matches[2];

					if ($op === '=')
					{
						$op = '==';
					}

					if ($op !== '')
					{
						call_user_func([$criteria, $attribute], $op, is_numeric($value) ? floatval($value) : $value);
					}
					else
					{
						$criteria->$attribute = new MongoRegex($caseSensitive ? '/' . $this->$attribute . '/' : '/' . $this->$attribute . '/i');
					}
				}
			}
		}

		$this->setDbCriteria($criteria);

		return new DataProvider($this);
	}

	/**
	 * Returns the static model of the specified Document class.
	 * The model returned is a static instance of the Document class.
	 * It is provided for invoking class-level methods (something similar to static class methods.)
	 * @param string $lang
	 * @return Document model instance.
	 */
	public static function model($lang = null)
	{
		$className = get_called_class();
		if (isset(self::$_models[$className]))
		{
			self::$_models[$className]->setLang($lang);
			return self::$_models[$className];
		}
		else
		{
			$model = self::$_models[$className] = new $className(null, $lang);
			$model->attachBehaviors($model->behaviors());
			return $model;
		}
	}

}
