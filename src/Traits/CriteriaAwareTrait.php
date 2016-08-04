<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Interfaces\CriteriaInterface;

/**
 * The most simple Criteria Aware Interface implementation.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait CriteriaAwareTrait
{

	/**
	 * Criteria instance holder.
	 * @var CriteriaInterface
	 */
	private $criteria = null;

	/**
	 * Get criteria
	 * @return CriteriaInterface
	 */
	public function getCriteria()
	{
		return $this->criteria;
	}

	/**
	 * Set criteria
	 * @param CriteriaInterface|array $criteria
	 * @return static
	 */
	public function setCriteria($criteria)
	{
		$this->criteria = $criteria;
		return $this;
	}

}
