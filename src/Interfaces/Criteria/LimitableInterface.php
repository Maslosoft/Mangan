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
interface LimitableInterface
{

	/**
	 * Set linit
	 * Multiple calls will overrride previous value of limit
	 *
	 * NOTE: This should be alias to setLimit
	 *
	 * @param integer $limit limit
	 * @return CriteriaInterface
	 */
	public function limit($limit);

	public function getLimit();

	/**
	 * @return CriteriaInterface
	 */
	public function setLimit($limit);

	/**
	 * Set offset
	 * Multiple calls will override previous value
	 *
	 * NOTE: This should be alias to setOffset
	 *
	 * @param int $offset offset
	 * @return CriteriaInterface
	 */
	public function offset($offset);

	public function getOffset();

	/**
	 * @return CriteriaInterface
	 */
	public function setOffset($offset);
}
