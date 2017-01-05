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
	 * Limit to `$num` results
	 *
	 * Note that cursor must have only limit method,
	 * do not use LimitableInterface etc.
	 *
	 * @param integer $num
	 * @return static
	 */
	public function limit($num);

	/**
	 * Skip `$num` number of results
	 * @param int $num
	 * @return static
	 */
	public function skip($num);

	/**
	 * Sort by fields
	 * @param array $fields
	 * @return static
	 */
	public function sort(array $fields);
}
