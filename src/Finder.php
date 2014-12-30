<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\EventDispatcher;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Transformers\FromRawArray;
use MongoException;
use MongoId;
use Yii;

/**
 * Finder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Finder
{

	const EventBeforeFind = "beforeFind";

	/**
	 * Model
	 * @var IAnnotated
	 */
	public $model = null;

	/**
	 *
	 * @var EntityManager
	 */
	public $em = null;

	/**
	 *
	 * @var EventDispatcher
	 */
	public $ed = null;

	/**
	 * Finder criteria
	 * @var Criteria
	 */
	private $_criteria = null;

	/**
	 * Current mdoel class
	 * @var string
	 */
	private $_class = '';

	/**
	 * Constructor
	 * @param Document $model
	 */
	public function __construct(EntityManager $em)
	{
		$this->model = $em->model;
		$this->em = $em;
		$this->_class = get_class($this->model);
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
		if (Event::hasHandler($this->model, self::EventBeforeFind))
		{
			$event = new ModelEvent($this, $this->model);
			Event::trigger($this->model, self::EventBeforeFind);
			if (!$event->handled)
			{
				return null;
			}
		}
		$this->applyScopes($criteria);
		$data = $this->em->getCollection()->findOne($criteria->getConditions(), $criteria->getSelect());
		return FromRawArray::toDocument($data);
	}

	/**
	 * Finds document with the specified primary key.
	 * See {@link find()} for detailed explanation about $criteria.
	 * @param mixed $pk primary key value(s). Use array for multiple primary keys. For composite key, each key value must be an array (column name=>column value).
	 * @param array|Criteria $criteria query criteria.
	 * @return the document found. An null is returned if none is found.
	 * @since v1.0
	 */
	public function findByPk($pk, $criteria = null)
	{

		$criteria = new Criteria($criteria);
		$criteria->mergeWith($this->createPkCriteria($pk));

		return $this->find($criteria);
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
		if ($this->beforeFind())
		{
			$this->applyScopes($criteria);
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
			if ($this->getMongoDBComponent()->enableProfiling)
			{
//				Yii::log($this->_class . '.findAll()' . var_export($cursor->explain(), true), CLogger::LEVEL_PROFILE, 'Maslosoft.Mangan.Document');
			}
			if ($this->getUseCursor())
			{
				return new Cursor($cursor, $this->model());
			}
			else
			{
				return $this->populateRecords($cursor);
			}
		}
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
//		if (($c = $this->model->getDbCriteria(false)) !== null)
//		{
//			$c->mergeWith($criteria);
//			$criteria = $c;
//			$this->_criteria = null;
//		}
	}

	/**
	 * Create primary key criteria.
	 * TODO Refactor
	 * @since v1.2.2
	 * @param mixed $pkValue Primary key value
	 * @return Criteria
	 * @throws MongoException
	 */
	private function createPkCriteria($pkValue)
	{
		$pkField = $this->em->meta->type()->primaryKey? : '_id';
		$criteria = new Criteria();

		if (is_array($pkField))
		{
			foreach ($pkField as $name)
			{
				if (!array_key_exists($name, $pkValue))
				{
					throw new CriteriaException(sprintf('Composite primary key field `%s` not specied for model `%s`, required fields: `%s`', $name, get_class($this->model), implode('`, `', $pkField)));
				}
				$this->_preparePk($name, $pkValue[$name], $criteria);
			}
		}
		else
		{
			$this->_preparePk($pkField, $pkValue, $criteria);
		}
		return $criteria;
	}

	/**
	 * Create pk criteria for single field
	 * @param string $name
	 * @param mixed $value
	 * @param Criteria $criteria
	 */
	private function _preparePk($name, $value, Criteria &$criteria)
	{
		$sanitizer = new Sanitizer($this->model);
		$criteria->$name = $sanitizer->write($name, $value);
	}

}
