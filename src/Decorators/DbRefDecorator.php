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

use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Mangan\Events\ClassNotFound;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\DbRefManager;
use Maslosoft\Mangan\Helpers\NotFoundResolver;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Interfaces\Decorators\Property\DecoratorInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Model\DbRef;
use function get_class;
use function gettype;
use function is_array;
use function json_encode;
use const JSON_PRETTY_PRINT;

/**
 * DbRefDecorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefDecorator implements DecoratorInterface
{

	public function read($model, $name, &$dbValue, $transformatorClass = TransformatorInterface::class)
	{
		if (empty($dbValue))
		{
			$fieldMeta = ManganMeta::create($model)->field($name);
			$model->$name = $fieldMeta->default;
			return;
		}

		/* @var $transformatorClass TransformatorInterface */

		// Assume that ref is already provided
		if (!empty($dbValue['_class']) && $dbValue['_class'] !== DbRef::class)
		{
			$model->$name = $transformatorClass::toModel($dbValue);
			return;
		}
		assert(is_array($dbValue), sprintf(
			'Expected array on `%s`, field `%s`, got: `%s`: `%s`',
			get_class($model),
			$name,
			gettype($dbValue),
			json_encode($dbValue, JSON_PRETTY_PRINT, 10)));
		$dbValue['_class'] = DbRef::class;
		$dbRef = $transformatorClass::toModel($dbValue);
		assert($dbRef instanceof DbRef);
		self::ensureClass($model, $name, $dbRef);
		/* @var $dbRef DbRef */
		$referenced = new $dbRef->class;
		$model->$name = (new Finder($referenced))->findByPk($dbRef->pk);
	}

	public function write($model, $name, &$dbValue, $transformatorClass = TransformatorInterface::class)
	{
		$fieldMeta = ManganMeta::create($model)->field($name);

		if (empty($model->$name))
		{
			if($fieldMeta->nullable)
			{
				$dbValue[$name] = null;
			}
			return;
		}
		$dbRef = DbRefManager::extractRef($model, $name);
		$referenced = $model->$name;

		if ($fieldMeta->dbRef->updatable)
		{
			DbRefManager::save($referenced, $dbRef);
		}
		/* @var $transformatorClass TransformatorInterface */
		$dbValue[$name] = $transformatorClass::fromModel($dbRef, false);
	}

	public static function ensureClass($model, $name, DbRef $dbRef): void
	{
		if (!ClassChecker::exists($dbRef->class))
		{
			$event = new ClassNotFound($model);
			$event->notFound = $dbRef->class;
			if (Event::hasHandler($model, NotFoundResolver::EventClassNotFound) && Event::handled($model, NotFoundResolver::EventClassNotFound, $event))
			{
				$dbRef->class = $event->replacement;
			}
			else
			{
				$pk = PkManager::getFromModel($model);
				$encodedPk = json_encode($pk);
				throw new ManganException(sprintf("Referenced model class `%s` not found in model `%s` field `%s`, pk: `%s`", $dbRef->class, get_class($model), $name, $encodedPk));
			}
		}
	}
}
