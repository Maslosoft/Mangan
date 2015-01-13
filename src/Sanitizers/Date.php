<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
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
