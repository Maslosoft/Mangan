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

namespace Maslosoft\Mangan\Helpers;

use MongoDB\BSON\ObjectId as MongoId;

/**
 * IdHelper
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IdHelper
{

	/**
	 * Check if provided value id mongo id compatible
	 * @param string|MongoId $mongoId
	 * @return boolean true if it's MongoId compatible string or MongoId instance
	 */
	public static function isId(MongoId|string $mongoId): bool
	{
		if ($mongoId instanceof MongoId)
		{
			return true;
		}
		if (preg_match('~^[a-f0-9]{24}$~', $mongoId))
		{
			return true;
		}
		return false;
	}

}
