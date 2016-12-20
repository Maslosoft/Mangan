<?php

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
	 * @param string[] $fields
	 * @return array
	 */
	public function findOne(CriteriaInterface $criteria, $fields = []);

	/**
	 * @param CriteriaInterface $criteria
	 * @param string[] $fields
	 * @return int
	 */
	public function count(CriteriaInterface $criteria);
}
