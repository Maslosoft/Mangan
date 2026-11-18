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

use DateTimeInterface;
use InvalidArgumentException;
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
	private function sanitize($value): UTCDateTime
	{
		if ($value instanceof UTCDateTime)
		{
			return $value;
		}
		if ($value instanceof DateTimeInterface)
		{
			return new UTCDateTime($value);
		}
		if (is_array($value))
		{
			// Canonical Extended JSON already contains milliseconds; do not round to seconds.
			if (isset($value['$date']['$numberLong']))
			{
				return new UTCDateTime((int) $value['$date']['$numberLong']);
			}
			if (isset($value['sec']))
			{
				return new UTCDateTime((int) $value['sec'] * 1000 + intdiv((int) ($value['usec'] ?? 0), 1000));
			}
			throw new InvalidArgumentException('Expected a date with sec/usec or $date/$numberLong fields.');
		}
		// Preserve the default of using the current time for an empty scalar value.
		if ($value === null || $value === '' || $value === false || $value === 0 || $value === '0')
		{
			return new UTCDateTime();
		}
		if (!is_numeric($value))
		{
			throw new InvalidArgumentException('Expected a Unix timestamp, UTCDateTime, or DateTimeInterface.');
		}
		return new UTCDateTime((int) round($value * 1000));
	}

}
