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

namespace Maslosoft\Mangan\Helpers;

/**
 * PropertyMaker
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PropertyMaker
{

	public static function defineProperty($object, $name, &$values = [])
	{
		$values[$name] = $object->$name;
		unset($object->$name);
	}

}
