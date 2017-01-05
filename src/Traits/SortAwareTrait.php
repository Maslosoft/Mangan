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

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Interfaces\SortInterface;
use Maslosoft\Mangan\Sort;

/**
 * SortAwareTrait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait SortAwareTrait
{

	/**
	 * @var SortInterface
	 */
	private $sort;

	/**
	 * Returns the sort object.
	 * @return Sort the sorting object. If this is false, it means the sorting is disabled.
	 */
	public function getSort()
	{
		if ($this->sort === null)
		{
			$this->sort = new Sort;
			$this->sort->setModel($this->getModel());
		}
		return $this->sort;
	}

	/**
	 * Set sort
	 * @param SortInterface $sort
	 * @return static
	 */
	public function setSort(SortInterface $sort)
	{
		$this->sort = $sort;
		$this->sort->setModel($this->getModel());
		return $this;
	}

	abstract public function getModel();
}
