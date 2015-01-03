<?php

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Meta\DbRefMeta;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * ReferenceAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefAnnotation extends ManganPropertyAnnotation
{

	public $class = '';
	public $value;

	public function init()
	{
		$data = [];
		foreach (['class', 'field', 'updatable'] as $key => $name)
		{
			if (isset($this->value[$key]))
			{
				$data[$name] = $this->value[$key];
				unset($this->value[$key]);
			}
			if (isset($this->value[$name]))
			{
				$data[$name] = $this->value[$name];
			}
		}

		$this->_entity->dbRef = new DbRefMeta($data);
	}

}
