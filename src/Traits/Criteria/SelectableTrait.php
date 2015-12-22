<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits\Criteria;

use Maslosoft\Mangan\Interfaces\Criteria\SelectableInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;

/**
 * SelectableTrait
 * @see CriteriaInterface
 * @see SelectableInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait SelectableTrait
{

	private $_select = [];

	/**
	 * List of fields to get from DB
	 * Multiple calls to this method will merge all given fields
	 *
	 * @param array $fields list of fields to select
	 * @return CriteriaInterface
	 */
	public function select(array $fields = null)
	{
		if ($fields !== null)
		{
			$this->setSelect(array_merge($this->_select, $fields));
		}
		return $this;
	}

	/**
	 * Return selected fields
	 * @return bool[] Fields used for select
	 * @since v1.3.1
	 */
	public function getSelect()
	{
		return $this->_select;
	}

	/**
	 * Set fields to select.
	 * Pass array with field names as keys and true as value, ie:
	 * ```php
	 * $criteria->setSelect(['_id' => true, 'title' => true]);
	 * ```
	 *
	 * NOTE: This resets entire select
	 *
	 * @param bool[] $select Fields to select
	 * @return CriteriaInterface
	 */
	public function setSelect(array $select)
	{
		$this->_select = [];
		// Convert the select array to field=>true/false format
		foreach ($select as $key => $value)
		{
			if (is_int($key))
			{
				$this->_select[$value] = true;
			}
			else
			{
				$this->_select[$key] = $value;
			}
		}
		return $this;
	}

}
