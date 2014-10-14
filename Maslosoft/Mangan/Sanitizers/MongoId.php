<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Sanitizers;

/**
 * MongoId
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MongoId implements ISanitizer
{

	public function get($value)
	{
		return (string) $value;
	}

	public function set($value)
	{
		if (!$value instanceof MongoId)
		{
			$value = new MongoId($value);
		}
		return $value;
	}

}
