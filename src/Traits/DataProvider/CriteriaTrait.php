<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits\DataProvider;

use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Interfaces\Criteria\DecoratableInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;

/**
 * CriteriaTrait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait CriteriaTrait
{

	/**
	 * @var CriteriaInterface
	 */
	private $criteria;

	/**
	 * Returns the criteria.
	 * @return CriteriaInterface the query criteria
	 * @since v1.0
	 */
	public function getCriteria()
	{
		// Initialise empty criteria, so it's always available via this method call.
		if (empty($this->criteria))
		{
			$className = static::CriteriaClass;
			$this->criteria = new $className;
		}
		return $this->criteria;
	}

	/**
	 * Sets the query criteria.
	 * @param CriteriaInterface|array $criteria the query criteria. Array representing the MongoDB query criteria.
	 * @return static
	 */
	public function setCriteria($criteria)
	{
		if (is_array($criteria))
		{
			$className = static::CriteriaClass;
			$this->criteria = new $className($criteria);
		}
		elseif ($criteria instanceof CriteriaInterface)
		{
			$this->criteria = $criteria;
		}
		if ($this->criteria instanceof DecoratableInterface)
		{
			$this->criteria->decorateWith($this->getModel());
		}
		return $this;
	}

	/**
	 * @return AnnotatedInterface
	 */
	abstract public function getModel();
}
