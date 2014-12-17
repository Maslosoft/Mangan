<?php

/**
 * @licence For licence @see LICENCE.html
 * 
 * @copyright Maslosoft
 * @link http://maslosoft.com/
 */

namespace Maslosoft\Mangan\Sanitizers;

/**
 * Integer
 * This sanitizer forces type to be integer
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Integer implements ISanitizer
{

	public function read($dbValue)
	{
		return (int) $dbValue;
	}

	public function write($phpValue)
	{
		return (int) $phpValue;
	}

}
