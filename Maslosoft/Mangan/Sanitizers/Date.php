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

	public function read($dbValue)
	{
		if ($dbValue instanceof MongoDate)
		{
			return $dbValue;
		}
		return new MongoDate((int) $dbValue);
	}

	public function write($phpValue)
	{
		if ($phpValue instanceof MongoDate)
		{
			return $phpValue;
		}
		return new MongoDate((int) $phpValue);
	}

}
