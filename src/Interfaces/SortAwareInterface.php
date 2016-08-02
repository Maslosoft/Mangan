<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
