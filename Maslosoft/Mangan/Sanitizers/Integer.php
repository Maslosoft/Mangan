<?php

/**
 * @licence For licence @see LICENCE.html
 * 
 * @copyright Maslosoft
 * @link http://maslosoft.com/
 */

namespace Maslosoft\Mangan\Sanitizers;

/**
 * Int
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Integer implements ISanitizer
{

	public function get($value)
	{
		return (int) $value;
	}

	public function set($value)
	{
		return (int) $value;
	}

}
