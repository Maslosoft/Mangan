<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces\Criteria;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface SelectableInterface
{

	/**
	 * Return selected fields
	 * @return bool[] Fields used for select
	 * @since v1.3.1
	 */
	public function getSelect();

	/**
	 * Set field to select.
	 * Pass array with field names as keys and true as value, ie:
	 * ```php
	 * $criteria->setSelect(['_id' => true, 'title' => true]);
	 * ```
	 *
	 * @param bool[] $select Fields to select
	 * @return CriteriaInterface
	 */
	public function setSelect(array $select);
}
