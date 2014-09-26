<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Sanitizers;

/**
 * EmbeddedArray
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmbeddedArray implements ISanitizer
{

	public function get($value)
	{
		return $value;
	}

	public function set($value)
	{
		return $value;
	}

}
