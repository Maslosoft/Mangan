<?php

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * ReferenceAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRef extends Maslosoft\Addendum\Collections\MetaAnnotation
{

	public $value;

	public function init()
	{
		// Target class
		$class = '';
		if(isset($this->value[0]))
		{
			$class = $this->value[0];
		}
		if(isset($this->value['class']))
		{
			$class = $this->value['class'];
		}

		// Target field
		$field = '';
		if(isset($this->value[1]))
		{
			$field = $this->value[1];
		}
		if(isset($this->value['field']))
		{
			$field = $this->value['field'];
		}

		// Updatable
		$updatable = '';
		if(isset($this->value[2]))
		{
			$updatable = $this->value[2];
		}
		if(isset($this->value['updatable']))
		{
			$updatable = $this->value['updatable'];
		}

		$this->_entity->dbRefClass = '';

		var_dump($class, $field, $updatable);
		var_dump($this->value);
	}

}
