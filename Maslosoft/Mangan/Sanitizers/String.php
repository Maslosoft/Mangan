<?php

/**
 * @licence For licence @see LICENCE.html
 * 
 * @copyright Maslosoft
 * @link http://maslosoft.com/
 */

namespace Maslosoft\Mangan\Sanitizers;

/**
 * String
 * This sanitizer forces values to be string
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class String implements ISanitizer
{

	public function read($model, $dbValue)
	{
		return (string) $dbValue;
	}

	public function write($model, $phpValue)
	{
		return (string) $phpValue;
	}

}
