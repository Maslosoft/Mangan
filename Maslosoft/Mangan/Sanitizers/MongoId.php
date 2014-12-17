<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Sanitizers;

/**
 * MongoId
 * This sanitizer forces MongoId type for both client and mongo
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MongoId implements ISanitizer
{

	public function read($dbValue)
	{
		if (!$dbValue instanceof MongoId)
		{
			$dbValue = new MongoId($dbValue);
		}
		return $dbValue;
	}

	public function write($phpValue)
	{
		if (!$phpValue instanceof MongoId)
		{
			$phpValue = new MongoId($phpValue);
		}
		return $phpValue;
	}

}
