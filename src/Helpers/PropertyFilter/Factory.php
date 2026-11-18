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

namespace Maslosoft\Mangan\Helpers\PropertyFilter;

use Maslosoft\Mangan\Helpers\RuntimeState;
use Maslosoft\Mangan\Interfaces\Filters\Property\TransformatorFilterInterface;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Mangan\Transformers\Filters\Unfiltered;

/**
 * Factory
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Factory
{

	/**
	 * Create filter
	 * @param string $transformatorClass
	 * @param DocumentTypeMeta $documentMeta
	 * @param DocumentPropertyMeta $fieldMeta
	 * @return Unfiltered|MultiFilter
	 */
	public static function create($transformatorClass, DocumentTypeMeta $documentMeta, DocumentPropertyMeta $fieldMeta)
	{
		$cache = &RuntimeState::cache(self::class . '::c');
		$key = serialize([$documentMeta->name, $documentMeta->connectionId, $fieldMeta->name, $transformatorClass]);

		if(isset($cache[$key]))
		{
			return $cache[$key];
		}

		$filterNames = self::getManganFilters($documentMeta->connectionId, $transformatorClass);
		if (count($filterNames) > 0)
		{
			if (count($filterNames) > 1)
			{
				$filter = new MultiFilter($filterNames);
				$cache[$key] = $filter;
				return $filter;
			}
			$filter = current($filterNames);

			$cache[$key] = $filter;
			return $filter;
		}
		$cache[$key] = false;
		return false;
	}

	/**
	 * Get filters for connection and transformator class
	 * @param string $connectionId
	 * @param string $transformatorClass
	 * @return TransformatorFilterInterface[]
	 */
	private static function getManganFilters($connectionId, $transformatorClass)
	{
		$configs = &RuntimeState::cache(self::class . '::_configs');
		if (!isset($configs[$connectionId]))
		{
			$configs[$connectionId] = [];
		}
		if (!isset($configs[$connectionId][$transformatorClass]))
		{
			$configs[$connectionId][$transformatorClass] = [];
			$mangan = Mangan::fly($connectionId);
			$tranformator = new $transformatorClass;
			foreach ($mangan->filters as $implementer => $filterClasses)
			{
				foreach ($filterClasses as $filterClass)
				{
					if ($tranformator instanceof $implementer)
					{
						$configs[$connectionId][$transformatorClass][] = new $filterClass;
					}
				}
			}
		}
		return $configs[$connectionId][$transformatorClass];
	}

}
