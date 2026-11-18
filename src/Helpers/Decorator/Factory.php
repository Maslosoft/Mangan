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

namespace Maslosoft\Mangan\Helpers\Decorator;

use Maslosoft\Mangan\Helpers\RuntimeState;
use Maslosoft\Mangan\Decorators\Undecorated;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Interfaces\Decorators\Model\ModelDecoratorInterface;
use Maslosoft\Mangan\Interfaces\Decorators\Property\DecoratorInterface;
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
	 * Create decorator
	 * @param string $transformatorClass
	 * @param DocumentTypeMeta $modelMeta
	 * @param DocumentPropertyMeta $meta
	 * @return bool|CompoundDecorator|DecoratorInterface
	 */
	public static function createForField($transformatorClass, DocumentTypeMeta $modelMeta, DocumentPropertyMeta $meta)
	{
		$cache = &RuntimeState::cache(self::class . '::c');
		$key = serialize([$modelMeta->name, $modelMeta->connectionId, $meta->name, $transformatorClass]);

		if(isset($cache[$key]))
		{
			return $cache[$key];
		}

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
				$decorator = new CompoundDecorator($decorators);
				$cache[$key] = $decorator;
				return $decorator;
			}
		}
		$cache[$key] = false;
		return false;
	}

	/**
	 * Create decorators for model. This returns all de
	 * @param string $transformatorClass
	 * @param DocumentTypeMeta $modelMeta
	 * @return CompoundModelDecorator
	 */
	public static function createForModel($transformatorClass, DocumentTypeMeta $modelMeta)
	{
		$modelDecorators = &RuntimeState::cache(self::class . '::modelDecorators');
		if (!isset($modelDecorators[$modelMeta->connectionId]) || !isset($modelDecorators[$modelMeta->connectionId][$transformatorClass]))
		{

			$decorators = [];
			foreach (self::getManganDecorators($modelMeta->connectionId, $transformatorClass) as $decoratorName => $active)
			{
				if ((new ReflectionClass($decoratorName))->implementsInterface(ModelDecoratorInterface::class))
				{
					$decorators[] = new $decoratorName;
				}
			}
			$modelDecorators[$modelMeta->connectionId][$transformatorClass] = new CompoundModelDecorator($decorators);
		}
		return $modelDecorators[$modelMeta->connectionId][$transformatorClass];
	}

	/**
	 * Get mangan decorators for selected connection id
	 * @param string $connectionId
	 * @param string $transformatorClass
	 * @return bool[]
	 */
	private static function getManganDecorators($connectionId, $transformatorClass)
	{
		$configs = &RuntimeState::cache(self::class . '::configs');
		if (!isset($configs[$connectionId]))
		{
			$configs[$connectionId] = [];
		}
		if (!isset($configs[$connectionId][$transformatorClass]))
		{
			$configs[$connectionId][$transformatorClass] = [];
			$mangan = Mangan::fly($connectionId);
			$transformator = new $transformatorClass;
			foreach ($mangan->decorators as $implementer => $decoratorClasses)
			{
				foreach ($decoratorClasses as $decoratorClass)
				{
					if ($transformator instanceof $implementer)
					{
						$configs[$connectionId][$transformatorClass][$decoratorClass] = true;
					}
				}
			}
		}
		return $configs[$connectionId][$transformatorClass];
	}

}
