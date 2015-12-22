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
