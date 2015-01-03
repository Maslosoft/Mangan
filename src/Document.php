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

use Maslosoft\Mangan\Core\Component;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Interfaces\IActiveRecord;
use Maslosoft\Mangan\Meta\ManganMeta;
use MongoCursor;
use MongoDB;
use MongoException;
use MongoId;

/**
 * Document
 *
 * @property-read MongoDB $db
 * @since v1.0
 */
abstract class Document extends EmbeddedDocument implements IActiveRecord
{

	use \Maslosoft\Mangan\Traits\EntityManagerTrait,
	  \Maslosoft\Mangan\Traits\FinderTrait,
	  \Maslosoft\Mangan\Traits\CollectionNameTrait;

	/**
	 * Mongo id field
	 * @KoBindable(false)
	 * @Sanitizer('MongoObjectId')
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
	private $_criteria = null; // query criteria (used by finder only)

	/**
	 * Add scopes functionality.
	 * @since v1.0
	 */

//	public function __call($name, $parameters)
//	{
//		$scopes = $this->scopes();
//		if (isset($scopes[$name]))
//		{
//			$this->getDbCriteria()->mergeWith($scopes[$name]);
//			return $this;
//		}
//	}

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
		$this->setLang($lang);

		// internally used by populateRecord() and model()
		if ($scenario == null)
		{
			return;
		}

		$this->setScenario($scenario);
		$this->init();
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
//	public function scopes()
//	{
//		return [];
//	}

	/**
	 * Returns the default named scope that should be implicitly applied to all queries for this model.
	 * Note, default scope only applies to SELECT queries. It is ignored for INSERT, UPDATE and DELETE queries.
	 * The default implementation simply returns an empty array. You may override this method
	 * if the model needs to be queried with some default criteria (e.g. only active records should be returned).
	 * @return array the mongo criteria. This will be used as the parameter to the constructor
	 * of {@link Criteria}.
	 * @since v1.2.2
	 */
//	public function defaultScope()
//	{
//		return [];
//	}

	/**
	 * Resets all scopes and criteria applied including default scope.
	 *
	 * @return Document
	 * @since v1.0
	 */
//	public function resetScope()
//	{
//		$this->_criteria = new Criteria();
//		return $this;
//	}

	/**
	 * Applies the query scopes to the given criteria.
	 * This method merges {@link dbCriteria} with the given criteria parameter.
	 * It then resets {@link dbCriteria} to be null.
	 * @param Criteria|array $criteria the query criteria. This parameter may be modified by merging {@link dbCriteria}.
	 * @since v1.2.2
	 */
//	public function applyScopes(&$criteria)
//	{
//		if ($criteria === null)
//		{
//			$criteria = new Criteria();
//		}
//		elseif (is_array($criteria))
//		{
//			$criteria = new Criteria($criteria);
//		}
//		elseif (!($criteria instanceof Criteria))
//		{
//			throw new MongoException('Cannot apply scopes to criteria');
//		}
//		if (($c = $this->getDbCriteria(false)) !== null)
//		{
//			$c->mergeWith($criteria);
//			$criteria = $c;
//			$this->_criteria = null;
//		}
//	}

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
//	protected function beforeFind()
//	{
//		if ($this->hasEventHandler('onBeforeFind'))
//		{
//			$event = new ModelEvent($this);
//			$this->onBeforeFind($event);
//			return $event->isValid;
//		}
//		else
//		{
//			return true;
//		}
//	}

	/**
	 * This method is invoked after each record is instantiated by a find method.
	 * The default implementation raises the {@link onAfterFind} event.
	 * You may override this method to do postprocessing after each newly found record is instantiated.
	 * Make sure you call the parent implementation so that the event is raised properly.
	 * @since v1.0
	 */
//	protected function afterFind()
//	{
//		if ($this->hasEventHandler('onAfterFind'))
//		{
//			$this->onAfterFind(new ModelEvent($this));
//		}
//	}

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
//	public function populateRecord($document, $callAfterFind = true)
//	{
//		if ($document !== null)
//		{
//			$model = $this->instantiate($document);
//			$model->setScenario('update');
//			$model->init();
//
//			$model->attachBehaviors($model->behaviors());
//
//			if ($callAfterFind)
//			{
//				$model->afterFind();
//			}
//			return $model;
//		}
//		else
//		{
//			return null;
//		}
//	}

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
//	public function populateRecords($cursor, $callAfterFind = true, $index = null)
//	{
//		$records = [];
//		foreach ($cursor as $attributes)
//		{
//			if (($record = $this->populateRecord($attributes, $callAfterFind)) !== null)
//			{
//				if ($index === null)
//				{
//					$records[] = $record;
//				}
//				else
//				{
//					$records[$record->$index] = $record;
//				}
//			}
//		}
//		return $records;
//	}

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
//	public function search($caseSensitive = false)
//	{
//		$criteria = $this->getDbCriteria();
//
//		foreach ($this->getSafeAttributeNames() as $attribute)
//		{
//			if ($this->$attribute !== null && $this->$attribute !== '')
//			{
//				if (is_array($this->$attribute) || is_object($this->$attribute))
//				{
//					$criteria->$attribute = $this->$attribute;
//				}
//				else if (preg_match('/^(?:\s*(<>|<=|>=|<|>|=|!=|==))?(.*)$/', $this->$attribute, $matches))
//				{
//					$op = $matches[1];
//					$value = $matches[2];
//
//					if ($op === '=')
//					{
//						$op = '==';
//					}
//
//					if ($op !== '')
//					{
//						call_user_func([$criteria, $attribute], $op, is_numeric($value) ? floatval($value) : $value);
//					}
//					else
//					{
//						$criteria->$attribute = new MongoRegex($caseSensitive ? '/' . $this->$attribute . '/' : '/' . $this->$attribute . '/i');
//					}
//				}
//			}
//		}
//
//		$this->setDbCriteria($criteria);
//
//		return new DataProvider($this);
//	}

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
