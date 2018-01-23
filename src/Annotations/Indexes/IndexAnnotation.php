<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations\Indexes;

use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Mangan\Meta\IndexMeta;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;
use Maslosoft\Mangan\Sort;

/**
 * IndexAnnotation
 *
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IndexAnnotation extends ManganPropertyAnnotation
{

	const Ns = __NAMESPACE__;

	public $value;

	/**
	 * This can be either:
	 *
	 * * Empty - for simple ascending index
	 * * `Sort::SortAsc` - for simple ascending index
	 * * `Sort::SortDesc` - for simple descending index
	 * * `array` - for any other keys specification
	 *
	 * @var mixed
	 */
	public $keys;
	public $options;

	public function init()
	{
		if(isset($this->value['username']))
		{
			echo '';
		}
		$data = (object)ParamsExpander::expand($this, ['keys', 'options']);

		// Seems short notation for keys only
		if(empty($data->keys) && is_array($this->value))
		{
			foreach($this->value as $key => $sort)
			{
				if(is_string($key))
				{
					$data->keys[$key] = $sort;
				}
			}
		}

		$entity = $this->getEntity();
		$name = $entity->name;
		$keys = [];
		if(empty($data->keys))
		{
			$keys[$name] = Sort::SortAsc;
		}
		else
		{
			if(!is_array($data->keys))
			{
				$keys[$name] = $data->keys;
			}
			else
			{
				$keys = $data->keys;
			}
		}
		if(empty($data->options))
		{
			$data->options = [];
		}
		$entity->index[] = new IndexMeta($keys, $data->options);
	}

}
