<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers\Decorator;

/**
 * Factory
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Factory
{

	public static function create(MetaProperty $meta)
	{
		if ($meta->decorators)
		{
			return new Container($meta->decorators);
		}
		return new \Maslosoft\Mangan\Decorators\Void();
	}

}
