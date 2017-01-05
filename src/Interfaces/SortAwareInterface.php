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

namespace Maslosoft\Mangan\Interfaces;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface SortAwareInterface
{

	/**
	 * Returns the sort object.
	 * @return SortInterface the sorting object. If this is false, it means the sorting is disabled.
	 */
	public function getSort();

	/**
	 * Set sort
	 * @param SortInterface $sort
	 * @return static
	 */
	public function setSort(SortInterface $sort);
}
