<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan;

use CLogger;
use Maslosoft\Mangan\Events\EventDispatcher;
use MongoException;
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
	 * @var Document
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
		if ($this->model->beforeFind())
		{
			$this->ed->trigger(self::beforeFind, $model);
			$this->applyScopes($criteria);
			$doc = $this->model->getCollection()->findOne($criteria->getConditions(), $criteria->getSelect());
			return $this->em->populateRecord($doc);
		}
		return null;
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
			$cursor = $this->getCollection()->find($criteria->getConditions());

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
		if (($c = $this->model->getDbCriteria(false)) !== null)
		{
			$c->mergeWith($criteria);
			$criteria = $c;
			$this->_criteria = null;
		}
	}
}
