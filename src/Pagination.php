<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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

	private $size = PaginationInterface::DefaultPageSize;
	private $page = PaginationInterface::FirstPageId;
	private $total = 0;

	public function apply(LimitableInterface $criteria)
	{
		$criteria->setLimit($this->getLimit());
		$criteria->setOffset($this->getOffset());
	}

	public function getPages()
	{
		return intval(ceil($this->total / $this->size));
	}

	public function setCount($total)
	{
		$this->total = $total;
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
