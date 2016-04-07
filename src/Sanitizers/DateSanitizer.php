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

use Maslosoft\Mangan\Interfaces\Sanitizers\Property\SanitizerInterface;
use MongoDate;

/**
 * Date
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DateSanitizer implements SanitizerInterface
{

	public function read($model, $dbValue)
	{
		if ($dbValue instanceof MongoDate)
		{
			return $dbValue;
		}
		if ((int) $dbValue === 0)
		{
			$dbValue = time();
		}
		return new MongoDate($dbValue);
	}

	public function write($model, $phpValue)
	{
		if ($phpValue instanceof MongoDate)
		{
			return $phpValue;
		}
		if ((int) $phpValue === 0)
		{
			$phpValue = time();
		}
		return new MongoDate($phpValue);
	}

}
