<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers\PropertyFilter;

use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Transformers\Filters\ITransformatorFilter;

/**
 * Factory
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Factory
{

	/**
	 * Filter instances
	 * @var ITransformatorFilter[][]
	 */
	private static $_configs = [];

	public static function create($transformatorClass, DocumentTypeMeta $documentMeta, DocumentPropertyMeta $fieldMeta)
	{
		$filterNames = self::getManganFilters($documentMeta->connectionId, $transformatorClass);
		if ($filterNames)
		{
			if(count($filterNames) > 1)
			{
				return new MultiFilter($filterNames);
			}
			return current($filterNames);
		}
		return new Unfilrered();
	}

	private static function getManganFilters($connectionId, $transformatorClass)
	{
		if(!isset(self::$_configs[$connectionId]))
		{
			self::$_configs[$connectionId] = [];
		}
		if (!isset(self::$_configs[$connectionId][$transformatorClass]))
		{
			self::$_configs[$connectionId] = [];
			self::$_configs[$connectionId][$transformatorClass] = [];
			$mangan = new Mangan($connectionId);
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
