<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces\Criteria;

use Maslosoft\Mangan\Interfaces\CriteriaInterface;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface CursorAwareInterface
{

	/**
	 * Whenever to use cursor
	 * @return bool Whever to use Cursor
	 */
	public function getUseCursor();

	/**
	 * Use cursor for fetching data
	 * @param bool $useCursor Whenever to use cursor
	 * @return CriteriaInterface
	 */
	public function setUseCursor($useCursor);
}
