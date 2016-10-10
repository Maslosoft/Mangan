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

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;
use Maslosoft\Mangan\Sanitizers\PassThrough;
use UnexpectedValueException;

/**
 * Use `Sanitizer` annotation to enforce particular data type.
 *
 * There are numerous built-in sanitizers which can be used, as well as any
 * custom sanitizer can be build. First annotation value must be sanitizer class
 * literal, class name as string or short string literal based for built-in
 * sanitizers. Some sanitizers can also have some parameters.
 * To get list of parameters, read particular sanitizer documentation.
 *
 * **Note: There can be only one sanitizer per field.**
 *
 * Example usage:
 * ```
 * @Sanitizer(MongoObjectId)
 * ```
 *
 * For built-in sanitizers, also short string notation can be used,
 * without importing class:
 *
 * ```
 * @Sanitizer('MongoObjectId')
 * ```
 *
 * To skip variable sanitization either make default value `null` or define
 * `None` sanitizer:
 * ```
 * @Sanitizer(None)
 * @Sanitizer('None')
 * ```
 *
 * Example of using sanitizer with parameters:
 * ```
 * @Sanitizer(MongoObjectId, nullable = true)
 * ```
 *
 * @template Sanitizer(${SanitizerClass})
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SanitizerAnnotation extends ManganPropertyAnnotation
{

	public $value = null;
	public $class;

	public function init()
	{
		$params = [
			'class'
		];
		if (is_string($this->value))
		{
			$this->class = $this->value;
		}
		elseif (is_array($this->value))
		{
			foreach (array_keys($this->value) as $key)
			{
				if (!is_numeric($key))
				{
					$params[] = $key;
				}
			}
		}
		$config = ParamsExpander::expand($this, $params);
		if (empty($config['class']))
		{
			throw new UnexpectedValueException(sprintf('@Sanitizer expects class name for model `%s` field `%s`', $this->getMeta()->type()->name, $this->getEntity()->name));
		}
		elseif ($config['class'] !== 'None' && !ClassChecker::exists($config['class']) && !ClassChecker::exists(sprintf('%s\\%s', PassThrough::Ns, $config['class'])))
		{
			throw new UnexpectedValueException(sprintf('Class `%s` for @Sanitizer not found on model `%s` field `%s`', $config['class'], $this->getMeta()->type()->name, $this->getEntity()->name));
		}
		$this->getEntity()->sanitizer = $config;
	}

}
