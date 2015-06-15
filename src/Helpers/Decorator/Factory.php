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

namespace Maslosoft\Mangan\Helpers\Decorator;

use Maslosoft\Mangan\Decorators\Undecorated;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Interfaces\Decorators\Model\IModelDecorator;
use Maslosoft\Mangan\Interfaces\Decorators\Property\IDecorator;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use ReflectionClass;

/**
 * Factory for creating decorators
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

	/**
	 * Model decorators
	 * @var IModelDecorator[]
	 */
	private static $_modelDecorators = [];

	/**
	 * Create decorator
	 * @param string $transformatorClass
	 * @param DocumentTypeMeta $modelMeta
	 * @param DocumentPropertyMeta $meta
	 * @return Undecorated|CompoundDecorator|IDecorator
	 */
	public static function createForField($transformatorClass, DocumentTypeMeta $modelMeta, DocumentPropertyMeta $meta)
	{
		if ($meta->decorators)
		{
			$activeDecorators = self::getManganDecorators($modelMeta->connectionId, $transformatorClass);
			$decorators = [];
			/**
			 * TODO This is workaround, it not should be required to do array_unique
			 * Further investigation needed
			 */
			if (!is_array($meta->decorators))
			{
				throw new ManganException('Meta `decorators` should be array');
			}
			foreach (array_unique($meta->decorators) as $decoratorName)
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

	/**
	 * Create decorators for model. This returns all de
	 * @param string $transformatorClass
	 * @param DocumentTypeMeta $modelMeta
	 * @return CompoundModelDecorator
	 */
	public static function createForModel($transformatorClass, DocumentTypeMeta $modelMeta)
	{
		if (!isset(self::$_modelDecorators[$modelMeta->connectionId]) || !isset(self::$_modelDecorators[$modelMeta->connectionId][$transformatorClass]))
		{

			$decorators = [];
			foreach (self::getManganDecorators($modelMeta->connectionId, $transformatorClass) as $decoratorName => $active)
			{
				if ((new ReflectionClass($decoratorName))->implementsInterface(IModelDecorator::class))
				{
					$decorators[] = new $decoratorName;
				}
			}
			self::$_modelDecorators[$modelMeta->connectionId][$transformatorClass] = new CompoundModelDecorator($decorators);
		}
		return self::$_modelDecorators[$modelMeta->connectionId][$transformatorClass];
	}

	/**
	 * Get mangan decorators for selected connection id
	 * @param string $connectionId
	 * @param string $transformatorClass
	 * @return bool[]
	 */
	private static function getManganDecorators($connectionId, $transformatorClass)
	{
		if (!isset(self::$_configs[$connectionId]))
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
