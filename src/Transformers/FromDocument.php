<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Transformers;

use Maslosoft\Mangan\Helpers\Decorator\Decorator;
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Interfaces\IModel;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * This transforms document into mongodb insertable array
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class FromDocument
{

	/**
	 * Returns the given object as an associative array
	 * Fires beforeToArray and afterToArray events
	 * @param IModel|object $model
	 * @param bool $withClassName Whenever to include special _class field
	 * @return array an associative array of the contents of this object
	 * @since v1.0.8
	 */
	public static function toRawArray($model, $withClassName = true)
	{
		$meta = ManganMeta::create($model);
		$decorator = new Decorator($model);
		$arr = [];
		$sanitizer = new Sanitizer($model);
		foreach ($meta->fields() as $name => $field)
		{
			$model->$name = $sanitizer->write($name, $model->$name);
			$decorator->write($name, $arr[$name]);
		}
		if ($withClassName)
		{
			$arr['_class'] = get_class($model);
		}
		return $arr;
	}

}
