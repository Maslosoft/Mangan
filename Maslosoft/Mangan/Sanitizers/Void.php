<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Sanitizers;

/**
 * Void
 * Empty sanitizer, does not change anything
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Void implements ISanitizer
{
	public function read($dbValue)
	{
		return $dbValue;
	}

	public function write($phpValue)
	{
		return $phpValue;
	}

}
