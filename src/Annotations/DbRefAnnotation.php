<?php

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Mangan\Decorators\DbRefDecorator;
use Maslosoft\Mangan\Meta\DbRefMeta;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * ReferenceAnnotation
 * @template DbRef(${class}, ${field}, ${updatable})
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefAnnotation extends ManganPropertyAnnotation
{

	public $class;
	public $field;
	public $updatable;
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
			if(isset($this->$name))
			{
				$data[$name] = $this->$name;
			}
		}
		$refMeta = new DbRefMeta($data);
		$refMeta->single = true;
		$refMeta->isArray = false;
		$this->_entity->dbRef = $refMeta;
		$this->_entity->decorators[] = DbRefDecorator::class;
	}

}
