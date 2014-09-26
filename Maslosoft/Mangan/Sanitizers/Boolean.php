<?php

/**
 * @licence For licence @see LICENCE.html
 * 
 * @copyright Maslosoft
 * @link http://maslosoft.com/
 */

namespace Maslosoft\Mangan\Sanitizers;

/**
 * Bool
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Boolean implements ISanitizer
{

	public function get($value)
	{
		return (bool) $value;
	}

	public function set($value)
	{
		return (bool) $value;
	}

}
