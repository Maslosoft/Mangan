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
