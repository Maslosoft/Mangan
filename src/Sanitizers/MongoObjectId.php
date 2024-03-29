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
use MongoDB\BSON\ObjectId as MongoId;

/**
 * MongoObjectId
 * This sanitizer forces MongoId type for both client and mongo
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MongoObjectId implements SanitizerInterface
{
	const IdPattern = '~^[a-f0-9]{24}$~';

	/**
	 * Whenever allow nulls
	 * @var bool
	 */
	public $nullable = false;

	public function read($model, $dbValue)
	{
		return $this->_cast($dbValue);
	}

	public function write($model, $phpValue)
	{
		return $this->_cast($phpValue);
	}

	protected function _cast($value, $string = false)
	{
		if($string && is_string($value) && preg_match(self::IdPattern, $value))
		{
			return $value;
		}
		if (!$value instanceof MongoId)
		{
			if (is_array($value) && isset($value['$oid']))
			{
				$value = $value['$oid'];
			}
			if (is_object($value) && isset($value->{'$oid'}))
			{
				$value = $value->{'$oid'};
			}

			if (!preg_match(self::IdPattern, (string) $value))
			{
				$value = null;
			}
			if ($this->nullable && empty($value))
			{
				return null;
			}
			$value = new MongoId($value);
		}
		if ($string)
		{
			return (string) $value;
		}
		return $value;
	}

}
