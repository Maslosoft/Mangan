<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
