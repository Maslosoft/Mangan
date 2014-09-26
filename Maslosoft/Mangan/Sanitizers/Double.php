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

	public function get($value)
	{
		return (float) $value;
	}

	public function set($value)
	{
		return (float) $value;
	}

}
