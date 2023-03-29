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
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\UTCDateTime as MongoDate;

/**
 * Date
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DateSanitizer implements SanitizerInterface
{

	/**
	 * @param       $model
	 * @param mixed $dbValue
	 * @return MongoDate
	 */
	public function read($model, $dbValue)
	{
		return $this->sanitize($dbValue);
	}

	/**
	 * @param       $model
	 * @param mixed $phpValue
	 * @return MongoDate
	 */
	public function write($model, $phpValue)
	{
		return $this->sanitize($phpValue);
	}

	/**
	 * @param $value
	 * @return MongoDate
	 */
	private function sanitize($value)
	{
		$sec = $value;
		$usec = 0;
		if ($value instanceof UTCDateTime)
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
		return new UTCDateTime($sec * 1000);
	}

}
