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

use Iterator;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use MongoCursor;

/**
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface FinderAdapterInterface
{

	/**
	 * @param CriteriaInterface $criteria
	 * @param string[] $fields
	 * @return FinderCursorInterface|MongoCursor|Iterator
	 */
	public function findMany(CriteriaInterface $criteria, $fields = []);

	/**
	 * @param CriteriaInterface $criteria
	 * @param bool[] $fields
	 * @return array
	 */
	public function findOne(CriteriaInterface $criteria, $fields = []);

	/**
	 * @param CriteriaInterface $criteria
	 * @return int
	 */
	public function count(CriteriaInterface $criteria);
}
