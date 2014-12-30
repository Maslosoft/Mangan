<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Sanitizers;

use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;

/**
 * PassThrough
 * Empty sanitizer, does not change anything
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PassThrough implements ISanitizer
{

	/**
	 * For internal use, to provide namespace to Sanitizer
	 * @see Sanitizer
	 */
	const Ns = __NAMESPACE__;

	public function read($model, $dbValue)
	{
		return $dbValue;
	}

	public function write($model, $phpValue)
	{
		return $phpValue;
	}

}
