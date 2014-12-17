<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers\Decorator;

use Maslosoft\Mangan\Decorators\Undecorated;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;

/**
 * Factory
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Factory
{

	public static function create(DocumentPropertyMeta $meta)
	{
		if ($meta->decorators)
		{
			$decorators = [];
			foreach($meta->decorators as $decoratorName)
			{
				$decorators[] = new $decoratorName;
			}
			return new Container($decorators);
		}
		return new Undecorated();
	}

}
