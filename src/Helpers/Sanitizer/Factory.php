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

namespace Maslosoft\Mangan\Helpers\Sanitizer;

use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Mangan\Sanitizers\ArraySanitizer;
use Maslosoft\Mangan\Sanitizers\BooleanSanitizer;
use Maslosoft\Mangan\Sanitizers\DoubleSanitizer;
use Maslosoft\Mangan\Sanitizers\IntegerSanitizer;
use Maslosoft\Mangan\Sanitizers\PassThrough;
use Maslosoft\Mangan\Sanitizers\StringSanitizer;

/**
 * Factory
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Factory
{

	private static $sanitizers = [];
	private static $arraySanitizers = [];

	public static function create(DocumentPropertyMeta $meta, DocumentTypeMeta $modelMeta, $transformatorClass)
	{
		$sanitizer = self::_resolve($meta, $modelMeta);

		// Remap sanitizer if needed
		$map = Mangan::fly($modelMeta->connectionId)->sanitizersMap;
		if (isset($map[$transformatorClass]) && isset($map[$transformatorClass][$sanitizer]))
		{
			$sanitizer = $map[$transformatorClass][$sanitizer];
		}

		// Sanitize as array
		if ($meta->sanitizeArray)
		{
			if (!isset(self::$arraySanitizers[$sanitizer]))
			{
				self::$arraySanitizers[$sanitizer] = new ArraySanitizer(new $sanitizer);
			}
			return self::$arraySanitizers[$sanitizer];
		}

		// Sanitize scalar/single value
		if (!isset(self::$sanitizers[$sanitizer]))
		{
			self::$sanitizers[$sanitizer] = new $sanitizer;
		}
		return self::$sanitizers[$sanitizer];
	}

	private static function _resolve(DocumentPropertyMeta $meta, DocumentTypeMeta $modelMeta)
	{
		// Sanitizer is explicitly set
		if ($meta->sanitizer)
		{
			// If short name is used add default namespace
			if (strstr($meta->sanitizer, '\\') === false)
			{
				$className = sprintf('%s\%s', PassThrough::Ns, $meta->sanitizer);
			}
			else
			{
				$className = $meta->sanitizer;
			}
			if (!ClassChecker::exists($className))
			{
				throw new ManganException(sprintf("Sanitizer class `%s` not found for field `%s` in model `%s`", $className, $meta->name, $modelMeta->name));
			}
			return $className;
		}

		// Guess sanitizer
		switch (gettype($meta->default))
		{
			case 'boolean':
				return BooleanSanitizer::class;
			case 'double':
				return DoubleSanitizer::class;
			case 'integer':
				return IntegerSanitizer::class;
			case 'string':
				return StringSanitizer::class;
		}

		// Fallback to passthrough
		return PassThrough::class;
	}

}
