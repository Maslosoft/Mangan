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
class MongoStringId extends MongoObjectId
{

	public function read($model, $dbValue)
	{
		return (string) $this->_cast($dbValue);
	}

	public function write($model, $phpValue)
	{
		return $this->_cast($phpValue);
	}

}
