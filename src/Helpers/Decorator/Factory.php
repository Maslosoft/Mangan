<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Helpers\Decorator;

use Maslosoft\Mangan\Decorators\Undecorated;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;

/**
 * Factory
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Factory
{

	/**
	 * Decorator class names
	 * @var bool[][][]
	 */
	private static $_configs = [];

	public static function create($transformatorClass, DocumentTypeMeta $modelMeta, DocumentPropertyMeta $meta)
	{
		if ($meta->decorators)
		{
			$activeDecorators = self::getManganDecorators($modelMeta->connectionId, $transformatorClass);
			$decorators = [];
			foreach ($meta->decorators as $decoratorName)
			{
				if (!isset($activeDecorators[$decoratorName]))
				{
					continue;
				}
				$decorators[] = new $decoratorName;
			}
			if ($decorators)
			{
				return new CompoundDecorator($decorators);
			}
		}
		return new Undecorated();
	}

	private static function getManganDecorators($connectionId, $transformatorClass)
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
			$transformator = new $transformatorClass;
			foreach ($mangan->decorators as $implementer => $decoratorClasses)
			{
				foreach ($decoratorClasses as $decoratorClass)
				{
					if ($transformator instanceof $implementer)
					{
						self::$_configs[$connectionId][$transformatorClass][$decoratorClass] = true;
					}
				}
			}
		}
		return self::$_configs[$connectionId][$transformatorClass];
	}

}
