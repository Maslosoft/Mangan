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

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Interfaces\ScenariosInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Transformers\RawArray;
use MongoCursor;

/**
 * Finder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Finder implements FinderInterface
{

	/**
	 * Model
	 * @var AnnotatedInterface
	 */
	public $model = null;

	/**
	 * Mangan instance
	 * @var Mangan
	 */
	private $mn = null;

	/**
	 * Entity manager instance
	 * @var EntityManagerInterface
	 */
	private $em = null;

	/**
	 * Scope manager instance
	 * @var ScopeManager
	 */
	private $sm = null;

	/**
	 * Finder criteria
	 * @var Criteria
	 */
	private $_criteria = null;

	/**
	 * Whenever to use corsors
	 * @var bool
	 */
	private $_useCursor = false;

	/**
	 * Constructor
	 *
	 * @param object $model Model instance
	 * @param EntityManagerInterface $em
	 * @param Mangan $mangan
	 */
	public function __construct($model, $em = null, $mangan = null)
	{
		$this->model = $model;
		$this->sm = new ScopeManager($model);
		if (null === $mangan)
		{
			$mangan = Mangan::fromModel($model);
		}
		$this->em = $em ? : EntityManager::create($model, $mangan);
		$this->mn = $mangan;
		$this->withCursor($this->mn->useCursor);
	}

	/**
	 * Create model related finder.
	 * This will create customized finder if defined in model with Finder annotation.
	 * If no custom finder is defined this will return default Finder.
	 *
	 * @param AnnotatedInterface $model
	 * @param EntityManagerInterface $em
	 * @param Mangan $mangan
	 * @return FinderInterface
	 */
	public static function create(AnnotatedInterface $model, $em = null, Mangan $mangan = null)
	{
		$finderClass = ManganMeta::create($model)->type()->finder ? : static::class;
		return new $finderClass($model, $em, $mangan);
	}

	/**
	 * Finds a single Document with the specified condition.
	 *
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @return AnnotatedInterface
	 * @Ignored
	 */
	public function find($criteria = null)
	{
		if ($this->_beforeFind())
		{
			$criteria = $this->sm->apply($criteria);
			$criteria->decorateWith($this->model);
			$data = $this->em->getCollection()->findOne($criteria->getConditions(), $criteria->getSelect());
			return $this->populateRecord($data);
		}
		return null;
	}

	/**
	 * Finds document with the specified primary key. Primary key by default
	 * is defined by `_id` field. But could be any other. For simple (one column)
	 * keys use it's value.
	 *
	 * For composite use key-value with column names as keys
	 * and values for values.
	 *
	 * Example for simple pk:
	 * ```php
	 * $pk = '51b616fcc0986e30026d0748'
	 * ```
	 *
	 * Composite pk:
	 * ```php
	 * $pk = [
	 * 		'mainPk' => 1,
	 * 		'secondaryPk' => 2
	 * ];
	 * ```
	 *
	 * @param mixed $pk primary key value. Use array for composite key.
	 * @param array|CriteriaInterface $criteria
	 * @return AnnotatedInterface|null
	 * @Ignored
	 */
	public function findByPk($pkValue, $criteria = null)
	{

		$pkCriteria = new Criteria($criteria);
		$pkCriteria->decorateWith($this->model);
		$pkCriteria->mergeWith(PkManager::prepare($this->model, $pkValue));

		return $this->find($pkCriteria);
	}

	/**
	 * Finds document with the specified attributes.
	 * Attributes should be specified as key-value pairs.
	 * This allows easier syntax for simple queries.
	 *
	 * Example:
	 * ```php
	 * $attributes = [
	 * 		'name' => 'John',
	 * 		'title' => 'dr'
	 * ];
	 * ```
	 *
	 * @param mixed[] Array of stributes and values in form of ['attributeName' => 'value']
	 * @return AnnotatedInterface|null
	 */
	public function findByAttributes(array $attributes)
	{
		$criteria = new Criteria();
		$criteria->decorateWith($this->model);
		foreach ($attributes as $name => $value)
		{
			$criteria->addCond($name, '==', $value);
		}
		return $this->find($criteria);
	}

	/**
	 * Finds all documents satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 *
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @return AnnotatedInterface[]|Cursor
	 */
	public function findAll($criteria = null)
	{
		if ($this->_beforeFind())
		{
			$criteria = $this->sm->apply($criteria);
			$criteria->decorateWith($this->model);
			$cursor = $this->em->getCollection()->find($criteria->getConditions());

			if ($criteria->getSort() !== null)
			{
				$cursor->sort($criteria->getSort());
			}
			if ($criteria->getLimit() !== null)
			{
				$cursor->limit($criteria->getLimit());
			}
			if ($criteria->getOffset() !== null)
			{
				$cursor->skip($criteria->getOffset());
			}
			if ($criteria->getSelect())
			{
				$cursor->fields($criteria->getSelect());
			}
			$this->mn->getProfiler()->cursor($cursor);
			if ($this->_useCursor)
			{
				return new Cursor($cursor, $this->model);
			}
			else
			{
				return $this->populateRecords($cursor);
			}
		}
		return [];
	}

	/**
	 * Finds all documents with the specified attributes.
	 *
	 * @param mixed[] Array of stributes and values in form of ['attributeName' => 'value']
	 * @return AnnotatedInterface[]|Cursor - Array or cursor of Documents
	 * @since v1.0
	 */
	public function findAllByAttributes(array $attributes)
	{
		$criteria = new Criteria();
		$criteria->decorateWith($this->model);
		foreach ($attributes as $name => $value)
		{
			$criteria->$name('==', $value);
		}

		return $this->findAll($criteria);
	}

	/**
	 * Finds all documents with the specified primary keys.
	 * In MongoDB world every document has '_id' unique field, so with this method that
	 * field is in use as PK by default.
	 * See {@link find()} for detailed explanation about $condition.
	 *
	 * @param mixed $pkValues primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @return AnnotatedInterface[]|Cursor - Array or cursor of Documents
	 * @since v1.0
	 */
	public function findAllByPk($pkValues, $criteria = null)
	{
		$pkCriteria = new Criteria($criteria);
		$pkCriteria->decorateWith($this->model);
		PkManager::prepareAll($this->model, $pkValues, $pkCriteria);

		return $this->findAll($pkCriteria);
	}

	/**
	 * Counts all documents satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|CriteriaInterface $criteria query criteria.
	 * @return integer Count of all documents satisfying the specified condition.
	 * @since v1.0
	 */
	public function count($criteria = null)
	{
		$criteria = $this->sm->apply($criteria);
		$criteria->decorateWith($this->model);
		return $this->em->getCollection()->count($criteria->getConditions());
	}

	/**
	 * Counts all documents found by attribute values.
	 *
	 * Example:
	 * ```php
	 * $attributes = [
	 * 		'name' => 'John',
	 * 		'title' => 'dr'
	 * ];
	 * ```
	 *
	 * @param mixed[] Array of attributes and values in form of ['attributeName' => 'value']
	 * @return int
	 * @since v1.2.2
	 * @Ignored
	 */
	public function countByAttributes(array $attributes)
	{
		$criteria = new Criteria;
		$criteria->decorateWith($this->model);
		foreach ($attributes as $name => $value)
		{
			$criteria->$name = $value;
		}

		$criteria = $this->sm->apply($criteria);

		return $this->em->getCollection()->count($criteria->getConditions());
	}

	/**
	 * Checks whether there is document satisfying the specified condition.
	 *
	 * @param CriteriaInterface $criteria
	 * @return bool
	 */
	public function exists(CriteriaInterface $criteria = null)
	{
		$criteria = $this->sm->apply($criteria);
		$criteria->decorateWith($this->model);
		/**
		 * TODO Use as limited fields set as possible here:
		 * Pk Fields, or only id, or fields used as query, still need to investigate
		 */
		$cursor = $this->em->getCollection()->find($criteria->getConditions());
		$cursor->limit(1);

		// NOTE: Cannot use count(true) here because of hhvm mongofill compatibility, see:
		// https://github.com/mongofill/mongofill/issues/86
		return ($cursor->count() !== 0);
	}

	/**
	 * Whenever to use cursor
	 * @param bool $useCursor
	 * @return FinderInterface
	 */
	public function withCursor($useCursor = true)
	{
		$this->_useCursor = $useCursor;
		return $this;
	}

	/**
	 * Resets all scopes and criteria applied including default scope.
	 *
	 * @return Finder
	 * @since v1.0
	 */
	public function resetScope()
	{
		$this->_criteria = new Criteria();
		$this->_criteria->decorateWith($this->model);
		return $this;
	}

	/**
	 * Creates an model with the given attributes.
	 * This method is internally used by the find methods.
	 * @param mixed[] $data attribute values (column name=>column value)
	 * @return AnnotatedInterface|null the newly created document. The class of the object is the same as the model class.
	 * Null is returned if the input data is false.
	 * @since v1.0
	 */
	protected function populateRecord($data)
	{
		if ($data !== null)
		{
			$model = RawArray::toModel($data, $this->model);
			ScenarioManager::setScenario($model, ScenariosInterface::Update);
			Event::trigger($model, FinderInterface::EventAfterFind);
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
	 * @return AnnotatedInterface[] array list of active records.
	 * @since v1.0
	 */
	protected function populateRecords(MongoCursor $cursor)
	{
		$records = array();
		foreach ($cursor as $data)
		{
			$records[] = $this->populateRecord($data);
		}
		return $records;
	}

	/**
	 * Trigger before find event
	 * @return boolean
	 */
	private function _beforeFind()
	{
		if (!Event::hasHandler($this->model, FinderInterface::EventBeforeFind))
		{
			return true;
		}
		return Event::handled($this->model, FinderInterface::EventBeforeFind);
	}

}
