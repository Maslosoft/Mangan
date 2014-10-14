<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan;

use MongoException;
use Yii;

/**
 * Finder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Finder
{
	/**
	 * Model
	 * @var Document
	 */
	public $model = null;

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
	public function __construct(Document $model)
	{
		$this->model = $model;
		$this->_class = get_class($model);
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
		Yii::trace($this->_class . '.find()', 'Maslosoft.Mangan.Document');

		if ($this->model->beforeFind())
		{
			$this->applyScopes($criteria);
			$doc = $this->model->getCollection()->findOne($criteria->getConditions(), $criteria->getSelect());
			return $this->populateRecord($doc);
		}
		return null;
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
