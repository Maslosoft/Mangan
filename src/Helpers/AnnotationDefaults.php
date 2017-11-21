<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 21.11.17
 * Time: 13:15
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