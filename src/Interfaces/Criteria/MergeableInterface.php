<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces\Criteria;

use Maslosoft\Mangan\Interfaces\CriteriaInterface;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface MergeableInterface
{

	/**
	 * Merge with other criteria
	 * - Field list operators will be merged
	 * - Limit and offet will be overriden
	 * - Select fields list will be merged
	 * - Sort fields list will be merged
	 * @param array|CriteriaInterface $criteria
	 * @return CriteriaInterface
	 */
	public function mergeWith($criteria);
}
