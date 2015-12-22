<?php

namespace Maslosoft\Mangan\Traits\Criteria;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Maslosoft\Mangan\Interfaces\Criteria\LimitableInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;

/**
 * LimitableTrait
 * @see LimitableInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait LimitableTrait
{

	private $_limit = null;
	private $_offset = null;

	/**
	 * Set linit
	 * Multiple calls will overrride previous value of limit
	 *
	 * @param integer $limit limit
	 * @return CriteriaInterface
	 */
	public function limit($limit)
	{
		$this->_limit = intval($limit);
		return $this;
	}

	/**
	 * Set offset
	 * Multiple calls will override previous value
	 *
	 * @return CriteriaInterface
	 */
	public function offset($offset)
	{
		$this->_offset = intval($offset);
		return $this;
	}

	/**
	 * @since v1.0
	 */
	public function getLimit()
	{
		return $this->_limit;
	}

	/**

	 * @return CriteriaInterface
	 */
	public function setLimit($limit)
	{
		$this->limit($limit);
		return $this;
	}

	/**
	 * @return CriteriaInterface
	 */
	public function getOffset()
	{
		return $this->_offset;
	}

	/**
	 * @since v1.0
	 */
	public function setOffset($offset)
	{
		$this->offset($offset);
		return $this;
	}

}
