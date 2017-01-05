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

namespace Maslosoft\Mangan\Interfaces\Adapters;

use Countable;
use Iterator;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;

/**
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface FinderCursorInterface extends Iterator, Countable
{

	/**
	 * Note that cursor must have only limit method,
	 * do not use LimitableInterface etc.
	 *
	 * @param integer $limit limit
	 * @return CriteriaInterface
	 */
	public function limit($limit);

	public function sort($sort);
}
