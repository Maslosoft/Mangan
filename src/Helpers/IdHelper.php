<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers;

use MongoId;

/**
 * IdHelper
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IdHelper
{

	/**
	 * Check if provided value id mongo id compatible
	 * @param MongoId $mongoId
	 * @return boolean true if it's MongoId compatible string or MongoId instance
	 */
	public static function isId($mongoId)
	{
		if ($mongoId instanceof MongoId)
		{
			return true;
		}
		if (preg_match('^[a-f0-9]{24}$', $mongoId))
		{
			return true;
		}
		return false;
	}

}
