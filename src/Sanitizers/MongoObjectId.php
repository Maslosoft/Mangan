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
 * MongoObjectId
 * This sanitizer forces MongoId type for both client and mongo
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MongoObjectId implements ISanitizer
{

	public function read($model, $dbValue)
	{
		if (!$dbValue instanceof MongoId)
		{
			$dbValue = new MongoId($dbValue);
		}
		return $dbValue;
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
