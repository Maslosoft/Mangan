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
