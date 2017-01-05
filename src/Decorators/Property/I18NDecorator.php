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

namespace Maslosoft\Mangan\Decorators\Property;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Interfaces\Decorators\Property\DecoratorInterface;
use Maslosoft\Mangan\Interfaces\InternationalInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * This creates i18n fields
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class I18NDecorator implements DecoratorInterface
{

	/**
	 * This will be called when getting value.
	 * This should return end user value.
	 * @param AnnotatedInterface $model Document model which will be decorated
	 * @param string $name Field name
	 * @param mixed $dbValue
	 * @return bool Return true if value should be assigned to model
	 */
	public function read($model, $name, &$dbValue, $transformatorClass = TransformatorInterface::class)
	{
		if (!$model instanceof InternationalInterface)
		{
			throw new ManganException(sprintf('Model class %s must implement interface %s to support I18N fields. You can use trait I18NAbleTrait as default implementation.', get_class($model), InternationalInterface::class));
		}
		$lang = $model->getLang();

		if (!is_array($dbValue))
		{
			$value = $dbValue;
			$dbValue = [];
			$dbValue[$lang] = $value;
		}

		$model->setRawI18N(array_merge($model->getRawI18N(), [$name => $dbValue]));
		if (array_key_exists($lang, $dbValue))
		{
			$model->$name = $dbValue[$lang];
		}
		else
		{
			$defaultLang = $model->getDefaultLanguage();
			$i18nMeta = ManganMeta::create($model)->field($name)->i18n;
			if ($i18nMeta->allowDefault && array_key_exists($defaultLang, $dbValue))
			{
				$model->$name = $dbValue[$defaultLang];
				return true;
			}
			if ($i18nMeta->allowAny)
			{
				foreach ($dbValue as $value)
				{
					if ($value)
					{
						$model->$name = $value;
					}
				}
			}
		}

		return true;
	}

	/**
	 * This will be called when setting value.
	 * This should return db acceptable value
	 * @param AnnotatedInterface $model Document model which will be decorated
	 * @param string $name Field name
	 * @param mixed $dbValue
	 * @return bool Return true to store value to database
	 */
	public function write($model, $name, &$dbValue, $transformatorClass = TransformatorInterface::class)
	{
		if (!$model instanceof InternationalInterface)
		{
			throw new ManganException(sprintf('Model class %s must implement interface %s to support I18N fields. You can use trait I18NAbleTrait as default implementation.', get_class($model), InternationalInterface::class));
		}
		foreach ($model->getRawI18N() as $field => $value)
		{
			// Skip non-18n field
			if ($field !== $name)
			{
				continue;
			}
			foreach ($value as $code => $string)
			{
				$dbValue[$name][$code] = $string;
			}
		}
		$dbValue[$name][$model->getLang()] = $model->$name;
		return true;
	}

}
