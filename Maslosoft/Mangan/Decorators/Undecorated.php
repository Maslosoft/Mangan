<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Decorators;

/**
 * Undecorated
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Undecorated implements IDecorator
{

	public function read($model, $name, $value)
	{
		return $value;
	}

	public function write($model, $name, $value)
	{
		return $value;
	}

}
