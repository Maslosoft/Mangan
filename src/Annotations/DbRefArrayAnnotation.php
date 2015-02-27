<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Annotations;

use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Mangan\Decorators\DbRefArrayDecorator;
use Maslosoft\Mangan\Meta\DbRefMeta;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * DB reference array annotation
 * @template DbRefArray(${class}, ${updatable})
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefArrayAnnotation extends ManganPropertyAnnotation
{

	public $value = [];

	public function init()
	{
		$data = ParamsExpander::expand($this, ['class', 'updatable']);
		$refMeta = new DbRefMeta($data);
		if (!$refMeta->class)
		{
			$refMeta->class = get_class($this->_component);
		}
		$refMeta->single = false;
		$refMeta->isArray = true;
		$this->_entity->dbRef = $refMeta;
		$this->_entity->decorators[] = DbRefArrayDecorator::class;
	}

}
