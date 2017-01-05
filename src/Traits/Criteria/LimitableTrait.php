<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Traits\Criteria;

use Maslosoft\Mangan\Interfaces\Criteria\LimitableInterface;

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
	 * Set limit
	 * Multiple calls will overrride previous value of limit.
	 *
	 * Pass `false` to disable limit.
	 *
	 * @param integer|bool $limit limit
	 * @return static
	 */
	public function limit($limit)
	{
		if (false === $limit)
		{
			$this->_limit = null;
			return $this;
		}
		$this->_limit = intval($limit);
		return $this;
	}

	/**
	 * Set offset
	 * Multiple calls will override previous value
	 *
	 * Pass `false` to disable offset.
	 *
	 * @return static
	 */
	public function offset($offset)
	{
		if (false === $offset)
		{
			$this->_offset = null;
			return $this;
		}
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

	 * @return static
	 */
	public function setLimit($limit)
	{
		$this->limit($limit);
		return $this;
	}

	/**
	 * @return static
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
