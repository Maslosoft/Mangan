<?php

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
