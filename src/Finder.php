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

	const beforeFind = "beforeFind";

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
		if (Event::hasHandler($this->model, self::beforeFind))
		{
			$event = new ModelEvent($this, $this->model);
			Event::trigger($this->model, self::beforeFind);
			if (!$event->handled)
			{
				return null;
			}
		}
		$this->applyScopes($criteria);
		$data = $this->em->collection->findOne($criteria->getConditions(), $criteria->getSelect());
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
			$cursor = $this->em->collection->find($criteria->getConditions());

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
	 * @param mixed $pk Primary key value
	 * @param boolean $multiple Whether to find multiple records.
	 * @return Criteria
	 * @throws MongoException
	 */
	private function createPkCriteria($pk, $multiple = false)
	{
		$pkField = $this->em->meta->type()->primaryKey;
		$criteria = new Criteria();
		if (is_string($pkField))
		{
			if ('_id' === $pkField)
			{
				if ((strlen($pk) === 24) && !$pk instanceof MongoId)
				{
					// Assumption: if dealing with _id field and it's a 24-digit string .. should be an Mongo ObjectID
					Yii::trace($this->_class . ".createPkCriteria() .. converting key value ($pk) to MongoId", 'Maslosoft.Mangan.Document');
					$pk = new MongoId($pk);
				}
				elseif (is_numeric($pk))
				{
					// Assumption: need to bless as int, as string != int when looking up primary keys
					Yii::trace($this->_class . ".createPkCriteria() .. casting ($pk) to int", 'Maslosoft.Mangan.Document');
					$pk = (int) $pk;
				}
			}
			if (!$multiple)
			{
				$criteria->{$pkField} = $pk;
			}
			else
			{
				$criteria->{$pkField}('in', $pk);
			}
		}
		elseif (is_array($pkField))
		{
			if (!$multiple)
				for ($i = 0; $i < count($pkField); $i++)
				{
					$pkField = $pk[$i];
					if ('_id' === $pkField[$i] && !$pk[$i] instanceof MongoId)
					{
						$pk[$i] = new MongoId($pk[$i]);
					}
					$criteria->{$pkField[$i]} = $pk[$i];
				}
			else
			{
				throw new MongoException('Cannot create PK criteria for multiple composite key\'s (not implemented yet)');
			}
		}
		return $criteria;
	}

}
