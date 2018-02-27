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

namespace Maslosoft\Mangan\Transformers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Mangan\Events\ClassNotFound;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Exceptions\TransformatorException;
use Maslosoft\Mangan\Helpers\Decorator\Decorator;
use Maslosoft\Mangan\Helpers\Decorator\ModelDecorator;
use Maslosoft\Mangan\Helpers\Finalizer\FinalizingManager;
use Maslosoft\Mangan\Helpers\NotFoundResolver;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Helpers\PropertyFilter\Filter;
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * Transformer
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class Transformer
{

	/**
	 * Returns the given object as an associative array
	 * @param AnnotatedInterface|object $model
	 * @param string[] $fields Fields to transform
	 * @return array an associative array of the contents of this object
	 */
	public static function fromModel(AnnotatedInterface $model, $fields = [])
	{
		$meta = static::getMeta($model);
		$calledClass = get_called_class();
		$decorator = new Decorator($model, $calledClass, $meta);
		$md = new ModelDecorator($model, $calledClass, $meta);
		$sanitizer = new Sanitizer($model, $calledClass, $meta);
		$filter = new Filter($model, $calledClass, $meta);
		$arr = [];
		foreach ($meta->fields() as $name => $fieldMeta)
		{
			if (!empty($fields) && !in_array($name, $fields))
			{
				continue;
			}
			if (!$filter->fromModel($model, $fieldMeta))
			{
				continue;
			}
			$model->$name = $sanitizer->write($name, $model->$name);
			$decorator->write($name, $arr);
			$model->$name = $sanitizer->read($name, $model->$name);
		}
		$md->write($arr);
		return FinalizingManager::fromModel($arr, static::class, $model);
	}

	/**
	 * Create document from array
	 *
	 * @param mixed[] $data
	 * @param string|object $className
	 * @param AnnotatedInterface $instance
	 * @return AnnotatedInterface
	 * @throws TransformatorException
	 */
	public static function toModel($data, $className = null, AnnotatedInterface $instance = null)
	{
		$data = (array) $data;
		if (is_object($className))
		{
			$className = get_class($className);
		}
		if (!$className)
		{
			if (array_key_exists('_class', $data))
			{
				$className = $data['_class'];
			}
			else
			{
				if (null !== $instance)
				{
					$className = get_class($instance);
				}
				else
				{
					throw new TransformatorException('Could not determine document type');
				}
			}
		}
		if ($instance)
		{
			$model = $instance;
		}
		else
		{
			self::ensureClass($className);
			$model = new $className;
		}
		$meta = static::getMeta($model);
		$calledClass = get_called_class();
		$decorator = new Decorator($model, $calledClass, $meta);
		$md = new ModelDecorator($model, $calledClass, $meta);
		$sanitizer = new Sanitizer($model, $calledClass, $meta);
		$filter = new Filter($model, $calledClass, $meta);

		// Ensure that primary keys are processed first,
		// as in some cases those could be processed *after* related
		// document(s), which results in wrong _id (or pk) being passed.
		$fieldsMeta = (array) $meta->fields();
		$pks = (array)PkManager::getPkKeys($model);
		foreach($pks as $key)
		{
			if(!array_key_exists($key, $fieldsMeta))
			{
				continue;
			}
			$pkMeta = $fieldsMeta[$key];
			unset($fieldsMeta[$key]);
			$fieldsMeta = array_merge([$key => $pkMeta], $fieldsMeta);
		}

		foreach ($fieldsMeta as $name => $fieldMeta)
		{
			/* @var $fieldMeta DocumentPropertyMeta */
			if (array_key_exists($name, $data))
			{
				// Value is available in passed data
				$value = $data[$name];
			}
			elseif (!empty($instance))
			{
				// Take value from existing instance
				// NOTE: We could `continue` here but value should be sanitized anyway
				$value = $model->$name;
			}
			else
			{
				// As a last resort set to default
				$value = $fieldMeta->default;
			}
			if (!$filter->toModel($model, $fieldMeta))
			{
				continue;
			}
			$decorator->read($name, $value);
			$model->$name = $sanitizer->read($name, $model->$name);
		}
		$md->read($data);

		return FinalizingManager::toModel(static::class, $model);
	}

	/**
	 * @param AnnotatedInterface $model
	 * @return ManganMeta
	 */
	protected static function getMeta(AnnotatedInterface $model)
	{
		return ManganMeta::create($model);
	}

	protected static function ensureClass(&$class)
	{
		if (!ClassChecker::exists($class))
		{
			$event = new ClassNotFound;
			$event->notFound = $class;
			if (Event::hasHandler(AnnotatedInterface::class, NotFoundResolver::EventClassNotFound) && Event::handled(AnnotatedInterface::class, NotFoundResolver::EventClassNotFound, $event))
			{
				$class = $event->replacement;
			}
			else
			{
				$params = [
					$class,
					NotFoundResolver::class,
					NotFoundResolver::EventClassNotFound,
					AnnotatedInterface::class
				];
				$msg = vsprintf("Model class `%s` not found. Attach event handler for `%s::%s` event on `%s` if You changed name to handle this case.", $params);
				throw new ManganException($msg);
			}
		}
	}
}
