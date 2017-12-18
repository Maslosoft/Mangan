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

namespace Maslosoft\Mangan\Model\Command;

use ReflectionClass;

/**
 * DbModel
 *
 * @internal This is base class for database commands models
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class DbCommandModel
{

	public function toArray($except = [])
	{
		$result = [];
		foreach ((new ReflectionClass($this))->getProperties() as $info)
		{
			$name = $info->name;
			if (in_array($name, $except))
			{
				continue;
			}
			$value = $this->$name;
			if ($value instanceof self)
			{
				$value = $value->toArray();
			}
			$result[$name] = $value;
		}
		return $result;
	}

}
