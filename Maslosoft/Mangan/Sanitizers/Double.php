<?php

/**
 * @licence For licence @see LICENCE.html
 * 
 * @copyright Maslosoft
 * @link http://maslosoft.com/
 */

namespace Maslosoft\Mangan\Sanitizers;

/**
 * Float
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Double implements ISanitizer
{

	public function read($dbValue)
	{
		return (float) $dbValue;
	}

	public function write($phpValue)
	{
		return (float) $phpValue;
	}

}
