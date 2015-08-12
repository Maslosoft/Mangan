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
	 * @param object $model Model instance
	 * @param EntityManagerInterface $em
	 */
	public function __construct($model, $em = null)
	{
		$this->model = $model;
		$this->em = $em? : EntityManager::create($model);
		$this->sm = new ScopeManager($model);
		$this->mn = Mangan::fromModel($model);
		$this->withCursor($this->mn->useCursor);
	}

	/**
	 * Create model related finder.
	 * This will create customized finder if defined in model with Finder annotation.
	 * If no custom finder is defined this will return default Finder.
	 * @param AnnotatedInterface $model
	 * @return FinderInterface
	 */
	public static function create(AnnotatedInterface $model)
	{
		$finderClass = ManganMeta::create($model)->type()->finder? : Finder::class;
		return new $finderClass($model);
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
	 * Finds document with the specified primary key.
	 * See {@link find()} for detailed explanation about $criteria.
	 * @param mixed $pkValue primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|Criteria $criteria query criteria.
	 * @return Document the document found. An null is returned if none is found.
	 * @since v1.0
	 */
	public function findByPk($pkValue, $criteria = null)
	{

		$pkCriteria = new Criteria($criteria);
		$pkCriteria->decorateWith($this->model);
		$pkCriteria->mergeWith(PkManager::prepare($this->model, $pkValue));

		return $this->find($pkCriteria);
	}

	/**
	 * Finds all documents satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|Criteria $criteria query criteria.
	 * @return AnnotatedInterface[]|Cursor list of documents satisfying the specified condition. An empty array is returned if none is found.
	 * @since v1.0
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
				$cursor->fields($criteria->getSelect(true));
			}
//			if ($this->getMongoDBComponent()->enableProfiling)
//			{
//				Yii::log($this->_class . '.findAll()' . var_export($cursor->explain(), true), CLogger::LEVEL_PROFILE, 'Maslosoft.Mangan.Document');
//			}
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
	 * @param mixed $pkValues primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|Criteria $criteria query criteria.
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
	 * Finds document with the specified attributes.
	 *
	 * See {@link find()} for detailed explanation about $condition.
	 * @param mixed[] Array of stributes and values in form of ['attributeName' => 'value']
	 * @return Document - the document found. An null is returned if none is found.
	 * @since v1.0
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
	 * Counts all documents satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|Criteria $criteria query criteria.
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
	 * Counts all documents satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param mixed[] Array of stributes and values in form of ['attributeName' => 'value']
	 * @return integer Count of all documents satisfying the specified condition.
	 * @since v1.2.2
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
	 * Checks whether there is row satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $criteria
	 * @param Criteria|null $criteria query condition or criteria.
	 * @return boolean whether there is row satisfying the specified condition.
	 */
	public function exists(Criteria $criteria = null)
	{
		$criteria = $this->sm->apply($criteria);
		$criteria->decorateWith($this->model);
		$cursor = $this->em->getCollection()->find($criteria->getConditions());
		$cursor->limit(1);
		return (bool) $cursor->count(true);
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
