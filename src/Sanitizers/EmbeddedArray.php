<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Sanitizers;

/**
 * EmbeddedArray
 * @deprecated since version number
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmbeddedArray implements ISanitizer
{

	public function read($model, $dbValue)
	{
		/**
		 * TODO Instantiate embedded array
		 */
		return $dbValue;
	}

	public function write($model, $phpValue)
	{
		/**
		 * TODO Convert embedded array into plain php array
		 */
		return $phpValue;
	}

}
