<?php

/**
 * @licence For licence @see LICENCE.html
 * 
 * @copyright Maslosoft
 * @link http://maslosoft.com/
 */

namespace Maslosoft\Mangan\Sanitizers;

use MongoDate;

/**
 * Date
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Date implements ISanitizer
{

	public function get($value)
	{
		if ($value instanceof MongoDate)
		{
			return $value;
		}
		return new MongoDate((int) $value);
	}

	public function set($value)
	{
		if ($value instanceof MongoDate)
		{
			return $value;
		}
		return new MongoDate((int) $value);
	}

}
