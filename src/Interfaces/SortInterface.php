<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface SortInterface
{

	/**
	 * Sort order, alias to Criteria sort constants
	 */
	const SortAsc = Criteria::SortAsc;
	const SortDesc = Criteria::SortDesc;

	public function setModel(AnnotatedInterface $model = null);
}
