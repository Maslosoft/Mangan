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
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class String implements ISanitizer
{

	public function get($value)
	{
		return (string) $value;
	}

	public function set($value)
	{
		return (string) $value;
	}

}
