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

namespace Maslosoft\Mangan\Interfaces;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface CriteriaAwareInterface
{

	/**
	 * Get criteria
	 * @return CriteriaInterface
	 */
	public function getCriteria();

	/**
	 * Set criteria. This accepts params of types:
	 *
	 * * CriteriaInterface - ready criteria object
	 * * array of criterias - params for criteria object - which will be instantiated internally
	 *
	 * @param CriteriaInterface|array $criteria
	 */
	public function setCriteria($criteria);
}
