<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Sanitizers;

/**
 * DbRef
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRef implements ISanitizer
{

	public function read($model, $dbValue)
	{
		/**
		 * TODO Find referenced document and instantiate it
		 */
		return $dbValue;
	}

	public function write($model, $phpValue)
	{
		/**
		 * TODO Retrieve `id` or other fields referencing document and document type
		 */
		return $phpValue;
	}

}
