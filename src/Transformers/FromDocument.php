<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Transformers;

use ArrayAccess;
use Maslosoft\Mangan\Helpers\Decorator\Decorator;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * This transforms document into mongodb insertable array
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class FromDocument
{

	/**
	 * Returns the given object as an associative array
	 * Fires beforeToArray and afterToArray events
	 * @return array an associative array of the contents of this object
	 * @since v1.0.8
	 */
	public static function toRawArray($document)
	{
		$meta = ManganMeta::create($document);
		$decorator = new Decorator($document);
		$arr = [];
		foreach ($meta->fields() as $name => $field)
		{
			$decorator->write($name, $arr[$name]);
		}
		$arr['_class'] = $meta->type()->name;
		return $arr;
	}

}
