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
