<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Extensions;

/**
 * Hasher
 * NOTE: Do NOT use in prodution, for testing purposes only!
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Hasher
{

	public static function hash()
	{
		return sha1(getmypid() . microtime() . getmyinode());
	}

}
