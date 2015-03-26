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

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Mangan\Events\ClassNotFound;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Helpers\NotFoundResolver;
use Maslosoft\Mangan\Interfaces\Decorators\Property\IDecorator;
use Maslosoft\Mangan\Interfaces\IOwnered;
use Maslosoft\Mangan\Interfaces\Transformators\ITransformator;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * EmbeddedDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmbeddedDecorator implements IDecorator
{

	public function read($model, $name, &$dbValue, $transformatorClass = ITransformator::class)
	{
		self::ensureClass($model, $name, $dbValue);
		$embedded = $transformatorClass::toModel($dbValue, $model->$name, $model->$name);
		if ($embedded instanceof IOwnered)
		{
			$embedded->setOwner($model);
		}
		$model->$name = $embedded;
	}

	public function write($model, $name, &$dbValue, $transformatorClass = ITransformator::class)
	{
		if (null === $model->$name)
		{
			$fieldMeta = ManganMeta::create($model)->$name;
			/* @var $fieldMeta DocumentPropertyMeta */
			$className = $fieldMeta->embedded->class;
			if (!is_string($className))
			{
				return null;
			}
			$dbValue[$name] = $transformatorClass::fromModel(new $className);
			return;
		}
		$dbValue[$name] = $transformatorClass::fromModel($model->$name);
	}

	public static function ensureClass($model, $name, &$dbValue)
	{
		if (!array_key_exists('_class', $dbValue))
		{
			$fieldMeta = ManganMeta::create($model)->$name;
			/* @var $fieldMeta DocumentPropertyMeta */
			$class = $fieldMeta->embedded->class;
		}
		else
		{
			$class = $dbValue['_class'];
		}
		if (!class_exists($class))
		{
			$event = new ClassNotFound($model);
			$event->notFound = $class;
			if (Event::hasHandler($model, NotFoundResolver::EventClassNotFound) && Event::handled($model, NotFoundResolver::EventClassNotFound, $event))
			{
				$class = $event->replacement;
			}
			else
			{
				throw new ManganException(sprintf("Embedded model class `%s` not found in model `%s` field `%s`", $class, get_class($model), $name));
			}
		}
		$dbValue['_class'] = $class;
	}

}
