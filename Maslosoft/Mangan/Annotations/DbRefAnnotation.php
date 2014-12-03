<?php

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;
use Maslosoft\Mangan\Meta\DbRefMeta;

/**
 * ReferenceAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRef extends MetaAnnotation
{
	public $class = '';
	public $value;

	public function init()
	{
		$data = [];
		foreach(['class', 'field', 'updatable'] as $key => $name)
		{
			if(isset($this->value[$key]))
			{
				$data[$name] = $this->value[$key];
				unset($this->value[$key]);
			}
			if(isset($this->value[$name]))
			{
				$data[$name] = $this->value[$name];
			}
		}

		$this->_entity->dbRef = new DbRefMeta($data);
	}

}
