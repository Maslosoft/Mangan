<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * Sanitizer. There can be only one sanitizer per field.
 * @template Sanitizer(${SanitizerClass})
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SanitizerAnnotation extends ManganPropertyAnnotation
{

	public $value = null;
	public $class;

	public function init()
	{
		$params = [
			'class'
		];
		if (is_string($this->value))
		{
			$this->class = $this->value;
		}
		elseif (is_array($this->value))
		{
			foreach (array_keys($this->value) as $key)
			{
				if (!is_numeric($key))
				{
					$params[] = $key;
				}
			}
		}
		$config = ParamsExpander::expand($this, $params);
		$this->_entity->sanitizer = $config;
	}

}
