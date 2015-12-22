<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits\Criteria;

use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Interfaces\Criteria\CursorAwareInterface;

/**
 * CursorAwareTrait
 * @see CursorAwareInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait CursorAwareTrait
{

	private $_useCursor = null;

	/**
	 * Whenever to use cursor
	 * @return bool Whever to use Cursor
	 */
	public function getUseCursor()
	{
		return $this->_useCursor;
	}

	/**
	 * Use cursor for fetching data
	 * @param bool $useCursor Whenever to use cursor
	 * @return Criteria
	 */
	public function setUseCursor($useCursor)
	{
		$this->_useCursor = $useCursor;
		return $this;
	}

}
