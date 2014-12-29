<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers\Sanitizer;

use Maslosoft\Addendum\Collections\MetaProperty;
use Maslosoft\Mangan\Sanitizers\Boolean;
use Maslosoft\Mangan\Sanitizers\Double;
use Maslosoft\Mangan\Sanitizers\Integer;
use Maslosoft\Mangan\Sanitizers\String;
use Maslosoft\Mangan\Sanitizers\Void;

/**
 * Factory
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Factory
{

	public static function create(MetaProperty $meta)
	{
		if ($meta->sanitizer)
		{
			if (strstr($meta->sanitizer, '\\') === false)
			{
				$className = sprintf('Maslosoft\Mangan\Sanitizers\%s', $meta->sanitizer);
			}
			else
			{
				$className = $meta->sanitizer;
			}
			return new $className;
		}

		switch (gettype($meta->default))
		{
			case 'boolean':
				return new Boolean;
			case 'double':
				return new Double;
			case 'integer':
				return new Integer;
			case 'string':
				return new String;
		}
		return new Void();
	}

}
