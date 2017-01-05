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

namespace Maslosoft\Mangan\Interfaces\Criteria;

use Maslosoft\Mangan\Interfaces\CriteriaInterface;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface LimitableInterface
{

	/**
	 * Set linit
	 * Multiple calls will overrride previous value of limit
	 *
	 * NOTE: This should be alias to setLimit
	 *
	 * @param integer $limit limit
	 * @return CriteriaInterface
	 */
	public function limit($limit);

	public function getLimit();

	/**
	 * @return CriteriaInterface
	 */
	public function setLimit($limit);

	/**
	 * Set offset
	 * Multiple calls will override previous value
	 *
	 * NOTE: This should be alias to setOffset
	 *
	 * @param int $offset offset
	 * @return CriteriaInterface
	 */
	public function offset($offset);

	public function getOffset();

	/**
	 * @return CriteriaInterface
	 */
	public function setOffset($offset);
}
