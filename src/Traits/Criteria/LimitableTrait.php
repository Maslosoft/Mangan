<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Traits\Criteria;

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
