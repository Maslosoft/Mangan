<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface CriteriaAwareInterface
{

	/**
	 * Get criteria
	 * @return CriteriaInterface
	 */
	public function getCriteria();

	/**
	 * Set criteria. This accepts params of types:
	 *
	 * * CriteriaInterface - ready criteria object
	 * * array of criterias - params for criteria object - which will be instantiated internally
	 *
	 * @param CriteriaInterface|array $criteria
	 */
	public function setCriteria($criteria);
}
