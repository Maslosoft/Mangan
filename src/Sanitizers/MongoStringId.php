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

use MongoId;

/**
 * MongoStringId
 * This sanitizer provide mongo id as string, while saving to db as `ObjectId`
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MongoStringId implements ISanitizer
{

	public function read($model, $dbValue)
	{
		if (!$dbValue instanceof MongoId)
		{
			$dbValue = new MongoId($dbValue);
		}
		return (string) $dbValue;
	}

	public function write($model, $phpValue)
	{
		if (!$phpValue instanceof MongoId)
		{
			$phpValue = new MongoId($phpValue);
		}
		return $phpValue;
	}

}
