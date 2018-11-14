<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
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
		return (int) parent::write($model, $dbValue)->sec;
	}

}
