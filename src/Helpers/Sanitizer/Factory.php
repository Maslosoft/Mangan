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

namespace Maslosoft\Mangan\Helpers\Sanitizer;

use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Gazebo\PluginFactory;
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
	private static $c = [];
	public static function create(DocumentPropertyMeta $meta, DocumentTypeMeta $modelMeta, $transformatorClass)
	{
		$key = $modelMeta->name . $modelMeta->connectionId . $meta->name . $transformatorClass;

		if(isset(self::$c[$key]))
		{
			return self::$c[$key];
		}

		$sanitizerClass = self::_resolve($meta, $modelMeta);


		// Remap sanitizer if needed
		$mapConfig = [];
		$map = Mangan::fly($modelMeta->connectionId)->sanitizersMap;
		if (isset($map[$transformatorClass]) && isset($map[$transformatorClass][$sanitizerClass]))
		{
			$mapConfig = $map[$transformatorClass][$sanitizerClass];
			if (is_string($mapConfig))
			{
				$mapClass = $mapConfig;
				$mapConfig = [
					'class' => $mapClass
				];
			}
		}
		if (is_array($meta->sanitizer))
		{
			$sanitizerConfig = $meta->sanitizer;
			$sanitizerConfig['class'] = $sanitizerClass;
		}
		else
		{
			$sanitizerConfig = [];
			$sanitizerConfig['class'] = $sanitizerClass;
		}
		if (!empty($mapConfig))
		{
			$sanitizerConfig = array_merge($sanitizerConfig, $mapConfig);
		}

		// Sanitize as array
		if ($meta->sanitizeArray)
		{
			$sanitizerConfig = [
				'class' => ArraySanitizer::class,
				'sanitizer' => $sanitizerConfig
			];
		}
		$config = [
			$transformatorClass => [
				$sanitizerConfig
			]
		];
		$sanitizer = PluginFactory::fly($modelMeta->connectionId)->instance($config, $transformatorClass)[0];
		self::$c[$key] = $sanitizer;
		return $sanitizer;
	}

	private static function _resolve(DocumentPropertyMeta $meta, DocumentTypeMeta $modelMeta)
	{
		// Sanitizer is explicitly set
		if (!empty($meta->sanitizer))
		{
			if (is_array($meta->sanitizer))
			{
				$name = $meta->sanitizer['class'];
			}
			else
			{
				$name = $meta->sanitizer;
			}
			// If short name is used add default namespace
			if (strstr($name, '\\') === false)
			{
				$className = sprintf('%s\%s', PassThrough::Ns, $name);
			}
			else
			{
				$className = $name;
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
