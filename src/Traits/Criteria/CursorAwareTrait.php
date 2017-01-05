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

namespace Maslosoft\Mangan\Traits\Criteria;

use Maslosoft\Mangan\Interfaces\Criteria\CursorAwareInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;

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
	 * @return CriteriaInterface
	 */
	public function setUseCursor($useCursor)
	{
		$this->_useCursor = $useCursor;
		return $this;
	}

}
