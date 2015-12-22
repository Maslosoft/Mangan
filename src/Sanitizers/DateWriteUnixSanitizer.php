<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Sanitizers;

use MongoDate;

/**
 * UnixDateSanitizer
 *
 * This sanitizer allow accessing date in php like a unix timestamp,
 * while storing it as MongoDate object.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DateWriteUnixSanitizer extends DateSanitizer
{

	public function write($model, $dbValue)
	{
		if ($dbValue instanceof MongoDate)
		{
			return (int) $dbValue->sec;
		}
		return (int) (new MongoDate((int) $dbValue))->sec;
	}

}
