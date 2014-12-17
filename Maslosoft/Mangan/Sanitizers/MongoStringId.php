<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Sanitizers;

/**
 * MongoStringId
 * This sanitizer provide mongo id as string, while saving to db as `ObjectId`
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MongoStringId extends MongoId
{

	public function read($dbValue)
	{
		if (!$dbValue instanceof MongoId)
		{
			$dbValue = new MongoId($dbValue);
		}
		return (string) $dbValue;
	}

}
