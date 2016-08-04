<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
