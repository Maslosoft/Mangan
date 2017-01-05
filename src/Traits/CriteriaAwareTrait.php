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

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Interfaces\CriteriaInterface;

/**
 * The most simple Criteria Aware Interface implementation.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait CriteriaAwareTrait
{

	/**
	 * Criteria instance holder.
	 * @var array|CriteriaInterface
	 */
	private $criteria = null;

	/**
	 * Get criteria
	 * @return array|CriteriaInterface
	 */
	public function getCriteria()
	{
		return $this->criteria;
	}

	/**
	 * Set criteria
	 * @param CriteriaInterface|array $criteria
	 * @return static
	 */
	public function setCriteria($criteria)
	{
		$this->criteria = $criteria;
		return $this;
	}

}
