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

namespace Maslosoft\Mangan\Helpers\PropertyFilter;

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
	 * Filter instances
	 * @var TransformatorFilterInterface[][]
	 */
	private static $_configs = [];

	/**
	 * Create filter
	 * @param string $transformatorClass
	 * @param DocumentTypeMeta $documentMeta
	 * @param DocumentPropertyMeta $fieldMeta
	 * @return Unfiltered|MultiFilter
	 */
	public static function create($transformatorClass, DocumentTypeMeta $documentMeta, DocumentPropertyMeta $fieldMeta)
	{
		$filterNames = self::getManganFilters($documentMeta->connectionId, $transformatorClass);
		if (count($filterNames) > 0)
		{
			if (count($filterNames) > 1)
			{
				return new MultiFilter($filterNames);
			}
			return current($filterNames);
		}
		return new Unfiltered();
	}

	/**
	 * Get filters for connection and transformator class
	 * @param string $connectionId
	 * @param string $transformatorClass
	 * @return TransformatorFilterInterface[]
	 */
	private static function getManganFilters($connectionId, $transformatorClass)
	{
		if (!isset(self::$_configs[$connectionId]))
		{
			self::$_configs[$connectionId] = [];
		}
		if (!isset(self::$_configs[$connectionId][$transformatorClass]))
		{
			self::$_configs[$connectionId] = [];
			self::$_configs[$connectionId][$transformatorClass] = [];
			$mangan = Mangan::fly($connectionId);
			$tranformator = new $transformatorClass;
			foreach ($mangan->filters as $implementer => $filterClasses)
			{
				foreach ($filterClasses as $filterClass)
				{
					if ($tranformator instanceof $implementer)
					{
						self::$_configs[$connectionId][$transformatorClass][] = new $filterClass;
					}
				}
			}
		}
		return self::$_configs[$connectionId][$transformatorClass];
	}

}
