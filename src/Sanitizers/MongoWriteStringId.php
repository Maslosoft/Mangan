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

/**
 * MongoStringId
 * This sanitizer provide mongo id as string, while saving to db as `ObjectId`
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MongoWriteStringId extends MongoObjectId
{

	public function write($model, $dbValue)
	{
		return $this->_cast($dbValue, true);
	}

}
