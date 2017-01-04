<?php

namespace Maslosoft\Mangan\Abstracts;

use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Interfaces\ModelAwareInterface;
use Maslosoft\Mangan\Traits\ModelAwareTrait;

/**
 * AbstractFinder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AbstractFinder implements ModelAwareInterface
{

	use ModelAwareTrait;

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
