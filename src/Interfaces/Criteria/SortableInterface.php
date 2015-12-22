<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces\Criteria;

use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\SortInterface;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface SortableInterface
{

	/**
	 * @since v1.0
	 */
	public function getSort();

	/**
	 * Set sorting of results. Use model field names as keys and Criteria's sort consntants.
	 *
	 * Afields will be automatically decorated according to model.
	 * For instance, when sorting on i18n field simply use field name, without language prefix.
	 *
	 * Sort by title example:
	 * ```php
	 * $criteria = new Criteria();
	 * $sort = [
	 * 		'title' => Criteria::SortAsc
	 * ];
	 * $criteria->setSort($sort);
	 * ```
	 * If title is declared as i18n and language is set to `en`, it will sort by `title.en` ascending in this case.
	 *
	 * Subsequent calls to setSort will override existing sort field and add new ones.
	 *
	 * Sort by title and then reverse order and add another field example:
	 * ```php
	 * $criteria = new Criteria();
	 * $sort = [
	 * 		'title' => Criteria::SortAsc
	 * ];
	 * $criteria->setSort($sort);
	 * // Override order and add second sort field
	 * $sort = [
	 * 		'title' => Criteria::SortDesc,
	 * 			'active' => Critera::SortAsc
	 * ];
	 * $criteria->setSort($sort);
	 * ```
	 * Will sort by title descending, then active ascending
	 *
	 * When using `Sort` object as param, it will replace entire sorting
	 * information with that provided by `Sort` instance.
	 *
	 * Sort by title and then replace with `Sort` instance example:
	 * ```php
	 * $criteria = new Criteria();
	 * $sort = [
	 * 		'title' => Criteria::SortAsc
	 * 			'active' => Critera::SortAsc
	 * ];
	 * $criteria->setSort($sort);
	 *
	 * // Override order completely with new Sort instance
	 * $sort = new Sort([
	 * 		'title' => Criteria::SortDesc,
	 * ];
	 * $criteria->setSort($sort);
	 * ```
	 * Will sort by title descending
	 *
	 *
	 * @param mixed[]|SortInterface
	 * @return CriteriaInterface
	 */
	public function setSort($sort);
}
