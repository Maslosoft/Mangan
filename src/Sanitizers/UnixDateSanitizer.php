<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Sanitizers;

use MongoDate;

/**
 * UnixDateSanitizer
 *
 * This sanitizer allow accessing date in php like a unix timestamp,
 * while storing it as MongoDate object.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class UnixDateSanitizer extends DateSanitizer
{

	public function read($model, $dbValue)
	{
		if ($dbValue instanceof MongoDate)
		{
			return $dbValue->sec;
		}
		return (new MongoDate((int) $dbValue))->sec;
	}

}
