<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Sanitizers;

/**
 * Embedded
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Embedded implements ISanitizer
{

	public function read($dbValue)
	{
		/**
		 * TODO Instantiate embedded
		 */
		return $dbValue;
	}

	public function write($phpValue)
	{
		/**
		 * TODO Convert embedded to array
		 */
		return $phpValue;
	}

}