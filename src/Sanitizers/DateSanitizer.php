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
		return $this->sanitize($dbValue);
	}

	public function write($model, $phpValue)
	{
		return $this->sanitize($phpValue);
	}

	private function sanitize($value)
	{
		$sec = $value;
		$usec = 0;
		if ($value instanceof MongoDate)
		{
			return $value;
		}
		if (is_array($value))
		{
			if (isset($value['sec']))
			{
				$sec = (int) $value['sec'];
			}
			if (isset($value['usec']))
			{
				$usec = (int) $value['usec'];
			}
		}
		if ((int) $value === 0)
		{
			$sec = time();
		}
		return new MongoDate($sec, $usec);
	}

}
