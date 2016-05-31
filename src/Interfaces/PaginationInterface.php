<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Mangan\Interfaces\Criteria\LimitableInterface;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface PaginationInterface
{

	const DefaultPageSize = 25;
	const FirstPageId = 1;

	public function getSize();

	/**
	 * Set size of page, how many items should appear on page.
	 * @param int $pageSize
	 */
	public function setSize($pageSize);

	public function getPage();

	/**
	 * Get pages
	 * @return int Number of pages
	 */
	public function getPages();

	/**
	 * Set page number
	 * @param int $page
	 */
	public function setPage($page);

	public function setCount($total);

	public function apply(LimitableInterface $criteria);

	public function getLimit();

	public function getOffset();
}
