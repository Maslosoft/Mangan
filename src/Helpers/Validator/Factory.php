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

namespace Maslosoft\Mangan\Helpers\Validator;

use InvalidArgumentException;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\Validators\ValidatorInterface;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\ValidatorMeta;

/**
 * Factory of validators
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Factory
{

	/**
	 * Create validator based on config.
	 * @param AnnotatedInterface $model
	 * @param ValidatorMeta $validatorMeta
	 * @return ValidatorInterface Validator instance
	 */
	public static function create(AnnotatedInterface $model, ValidatorMeta $validatorMeta, $fieldName = null)
	{
		$mn = Mangan::fromModel($model);
		// Resolve validator class
		if ($validatorMeta->proxy && empty($validatorMeta->class))
		{
			if (isset($mn->validators[$validatorMeta->proxy]))
			{
				$validatorMeta->class = $mn->validators[$validatorMeta->proxy];
			}
			else
			{
				if (empty($fieldName))
				{
					$fieldName = '<not provided>';
				}
				$args = [
					get_class($model),
					$validatorMeta->proxy,
					$fieldName
				];
				$msg = vsprintf("Could not resolve validator class from proxy. For model `%s`. Proxy class: `%s`. Model field: `%s`", $args);
				throw new InvalidArgumentException($msg);
			}
		}

		if (empty($validatorMeta->class))
		{
			$args = [
				get_class($model),
			];
			$msg = vsprintf("Empty validator class for model `%s`", $args);
			throw new InvalidArgumentException($msg);
		}
		$config = (array) $validatorMeta;
		unset($config['proxy']);
		$di = $mn->getDi();
		return $di->apply($config);
	}

}
