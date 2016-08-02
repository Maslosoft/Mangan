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

namespace Maslosoft\Mangan\Traits\Criteria;

use Exception;
use Maslosoft\Mangan\Interfaces\Criteria\DecoratableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\SortableInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\SortInterface;

/**
 * SortableTrait
 * @see SortableInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait SortableTrait
{

	private $_sort = [];

	/**
	 * Add sorting, available orders are: Criteria::SortAsc and Criteria::SortDesc
	 * Each call will be grouped with previous calls
	 * @param string $fieldName
	 * @param integer $order
	 * @return CriteriaInterface
	 * @since v1.0
	 */
	public function sort($fieldName, $order = SortInterface::SortAsc)
	{
		if ($this instanceof DecoratableInterface)
		{
			$decorated = $this->getCd()->decorate($fieldName);
			$this->_sort[key($decorated)] = intval($order);
			// NOTE: Ignore further bogus scrunitize report:
			// Accessing _sort on the interface Maslosoft\Mangan\Interfa...ia\DecoratableInterface
			// suggest that you code against a concrete implementation. How about adding an instanceof check?
		}
		else
		{
			$this->_sort[$fieldName] = intval($order);
		}
		return $this;
	}

	/**
	 * @since v1.0
	 */
	public function getSort()
	{
		return $this->_sort;
	}

	/**
	 * Set sorting of results. Use model field names as keys and Criteria's sort consntants.
	 *
	 * All fields will be automatically decorated according to model.
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
	public function setSort($sort)
	{
		if ($sort instanceof SortInterface)
		{
			$this->_sort = $sort->getSort();
		}
		else
		{
			if (!is_array($sort))
			{
				throw new Exception(sprintf('Sort must be instance of `%s` or array, `%s` given', SortInterface::class, gettype($sort)));
			}
			foreach ($sort as $fieldName => $order)
			{
				$this->sort($fieldName, $order);
			}
		}
		return $this;
	}

}
