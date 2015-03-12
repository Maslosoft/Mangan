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
use Maslosoft\Mangan\Decorators\DbRefDecorator;
use Maslosoft\Mangan\Meta\DbRefMeta;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * DB reference annotation
 * @template DbRef(${class}, ${updatable})
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefAnnotation extends ManganPropertyAnnotation
{

	public $class;
	public $updatable;
	public $value;

	public function init()
	{
		$refMeta = $this->_getMeta();
		$refMeta->single = true;
		$refMeta->isArray = false;
		$this->_entity->dbRef = $refMeta;
		$this->_entity->propagateEvents = true;
		$this->_entity->decorators[] = DbRefDecorator::class;
	}

	protected function _getMeta()
	{
		$data = ParamsExpander::expand($this, ['class', 'updatable']);
		$refMeta = new DbRefMeta($data);
		if (!$refMeta->class)
		{
			$refMeta->class = $this->_meta->type()->name;
		}
		return $refMeta;
	}

}
