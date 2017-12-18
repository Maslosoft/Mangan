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

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Interfaces\AnnotationInterface;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\ManganAnnotation;

class AnnotationDefaults
{
	public static function apply(ManganAnnotation $annotation, $data)
	{
		$defaults = Mangan::fly()->annotationsDefaults;
		$key = get_class($annotation);
		if(empty($defaults[$key]))
		{
			return;
		}
		assert(is_array($defaults[$key]));
		foreach($defaults[$key] as $name => $value)
		{
			// Don't set values provided from model
			if(array_key_exists($name, $data))
			{
				continue;
			}
			$annotation->$name = $value;
		}
	}
}