<?php

namespace Maslosoft\Mangan\Abstracts;

/**
 * AbstractFinder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AbstractFinder
{

	/**
	 * Whenever to use cursors
	 * @var bool
	 */
	private $useCursor = false;

	/**
	 * Whenever to use cursor
	 * @param bool $useCursor
	 * @return FinderInterface
	 */
	public function withCursor($useCursor = true)
	{
		$this->useCursor = $useCursor;
		return $this;
	}

	public function isWithCursor()
	{
		return $this->useCursor;
	}

}
