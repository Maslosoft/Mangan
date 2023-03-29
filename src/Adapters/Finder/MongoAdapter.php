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

namespace Maslosoft\Mangan\Adapters\Finder;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Interfaces\Adapters\FinderAdapterInterface;
use Maslosoft\Mangan\Interfaces\CriteriaInterface;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;
use Maslosoft\Mangan\Interfaces\SortInterface;
use Maslosoft\Mangan\Mangan;
use function Sodium\library_version_minor;

/**
 *
 * @internal This is adapter for mongo finder, do not use directly
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MongoAdapter implements FinderAdapterInterface
{

	/**
	 * Entity manager instance
	 * @var EntityManagerInterface
	 */
	private $em = null;

	public function __construct(AnnotatedInterface $model, Mangan $mangan, EntityManagerInterface $em = null)
	{
		$this->em = $em ?: EntityManager::create($model, $mangan);
	}

	public function count(CriteriaInterface $criteria)
	{
		return $this->em->getCollection()->count($criteria->getConditions());
	}

	public function findMany(CriteriaInterface $criteria, $fields = [], $options = [])
	{
		return $this->em->getCollection()->find($criteria->getConditions(), $options);
	}

	public function findOne(CriteriaInterface $criteria, $fields = [])
	{
		$options = [
			'sort' => $criteria->getSort(),
			'limit' => 1,
		];
		if(!empty($fields))
		{
			// FIXME: Check if $fields format is correct
			$otions['projection'] = $fields;
		}
		// Use find instead of findOne here so sort can be applied
		$cursor = $this->em->getCollection()->find($criteria->getConditions(), $options);
		foreach($cursor as $item)
		{
			return $item;
		}
		return null;
	}


}
