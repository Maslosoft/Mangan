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

namespace Maslosoft\Mangan\Decorators;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Mangan\Events\ClassNotFound;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Helpers\DbRefManager;
use Maslosoft\Mangan\Helpers\NotFoundResolver;
use Maslosoft\Mangan\Helpers\UnknownDocumentTypePanicker;
use Maslosoft\Mangan\Interfaces\Decorators\Property\DecoratorInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\ManganMeta;
use MongoDB\Model\BSONDocument;
use function get_class;
use function is_string;
use function strtolower;

/**
 * EmbeddedDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmbeddedDecorator implements DecoratorInterface
{

	public function read($model, $name, &$dbValue, $transformatorClass = TransformatorInterface::class)
	{
		if($dbValue instanceof BSONDocument)
		{
			$dbValue = (array)$dbValue;
		}
		if ($dbValue instanceof AnnotatedInterface)
		{
			$model->$name = $dbValue;
			return;
		}

		// Workaround for string null value
		if(is_string($dbValue) && strtolower($dbValue) === 'null')
		{
			$dbValue = null;
		}

		if($dbValue === null)
		{
			$meta = ManganMeta::create($model)->$name;
			assert($meta instanceof DocumentPropertyMeta);
			if($meta->nullable || ($meta->embedded && $meta->embedded->nullable))
			{
				$model->$name = null;
				return null;
			}
		}
		static::ensureClass($model, $name, $dbValue);
		$instance = null;
		if ($model->$name instanceof $dbValue['_class'])
		{
			$instance = $model->$name;
		}
		$embedded = $transformatorClass::toModel($dbValue, $instance, $instance);

		// Field was transformed from DB Ref
		$embedded = DbRefManager::maybeCreateInstanceFrom($embedded);

		$model->$name = $embedded;
	}

	public function write($model, $name, &$dbValue, $transformatorClass = TransformatorInterface::class)
	{
		if (null === $model->$name)
		{
			$meta = ManganMeta::create($model)->$name;
			assert($meta instanceof DocumentPropertyMeta);
			if($meta->nullable || ($meta->embedded && $meta->embedded->nullable))
			{
				$dbValue[$name] = null;
				return null;
			}
			$className = static::getClassName($model, $name);
			if (!is_string($className))
			{
				return null;
			}
			// This is to prevent infinite loops
			// if class name is same as current `$model`, ie not really set.
			// @link https://github.com/Maslosoft/Mangan/issues/86
			if(is_a($model, $className))
			{
				return null;
			}
			$dbValue[$name] = $transformatorClass::fromModel(new $className);
			return;
		}
		$dbValue[$name] = $transformatorClass::fromModel($model->$name);
	}

	public static function ensureClass($model, $name, &$dbValue): void
	{
		if (!is_array($dbValue) || !array_key_exists('_class', $dbValue) || empty($dbValue['_class']))
		{
			$class = static::getClassName($model, $name);
		}
		else
		{
			$class = $dbValue['_class'];
		}
		if (!ClassChecker::exists($class))
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

		if(is_string($dbValue) && strtolower($dbValue) === 'null')
		{
			$dbValue = null;
		}

		// Something is very wrong here.
		// The `$dbValue` variable must be array for embedded documents
		// if it is not, it means that we have wrong data type stored in
		// database. There is last resort handling below this condition
		// check, however it *might* be risky to use this, as we cannot
		// be sure that we can reconstruct proper object from some scalar value.
		if(!is_array($dbValue) && null !==$dbValue)
		{
			$data = $dbValue;
			$dbValue = [];
			$dbValue['data'] = $data;
			$class = UnknownDocumentTypePanicker::tryHandle($dbValue, $model, $name);
		}
		$dbValue['_class'] = $class;
	}

	protected static function getClassName($model, $name)
	{
		$fieldMeta = ManganMeta::create($model)->$name;

		/* @var $fieldMeta DocumentPropertyMeta */
		return $fieldMeta->embedded->class;
	}

}
