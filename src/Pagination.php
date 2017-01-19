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

namespace Maslosoft\Mangan;

use Maslosoft\Mangan\Interfaces\Criteria\LimitableInterface;
use Maslosoft\Mangan\Interfaces\PaginationInterface;

/**
 * Basic pagination class
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Pagination implements PaginationInterface
{

	public $size = PaginationInterface::DefaultPageSize;
	public $page = PaginationInterface::FirstPageId;
	public $total = 0;

	public function apply(LimitableInterface $criteria)
	{
		$limit = $criteria->getLimit();
		if (empty($limit))
		{
			$criteria->setLimit($this->getLimit());
		}
		else
		{
			$this->setSize($limit);
		}
		/**
		 * TODO Pagination should revert to max or min page if value if out of range #78
		 * @see https://github.com/Maslosoft/Mangan/issues/78
		 */
		$criteria->setOffset($this->getOffset());
	}

	public function getPages()
	{
		return intval(ceil($this->total / $this->size));
	}

	public function setCount($total)
	{
		// Ensure positive total
		$this->total = max($total, 0);
		// Recalculate max page, as order of setting
		// count or page might be different
		$this->page = max(min($this->getPages(), $this->page), 1);
		return $this;
	}

	public function getSize()
	{
		return $this->size;
	}

	public function getPage()
	{
		return $this->page;
	}

	public function setSize($size)
	{
		$this->size = $size;
		return $this;
	}

	public function setPage($page)
	{
		// See also setCount method
		$page = max(min($this->getPages(), $page), 1);
		$this->page = $page;
		return $this;
	}

	public function getLimit()
	{
		return $this->getSize();
	}

	public function getOffset()
	{
		// Pages are indexed from one, so substract 1 here
		return ($this->getPage() - 1) * $this->getSize();
	}

}
