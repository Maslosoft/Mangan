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

	public function read($model, $dbValue)
	{
		if ($dbValue instanceof MongoDate)
		{
			return $dbValue;
		}
		return new MongoDate((int) $dbValue);
	}

	public function write($model, $phpValue)
	{
		if ($phpValue instanceof MongoDate)
		{
			return $phpValue;
		}
		return new MongoDate((int) $phpValue);
	}

}
