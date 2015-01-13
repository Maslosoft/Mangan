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

use Maslosoft\Mangan\Decorators\DbRefDecorator;
use Maslosoft\Mangan\Meta\DbRefMeta;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * DB reference array annotation
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
		$refMeta = $this->_createMeta();
		$refMeta->single = true;
		$refMeta->isArray = false;
		$this->_entity->dbRef = $refMeta;
		$this->_entity->decorators[] = DbRefDecorator::class;
	}

	/**
	 * Create DBRefMeta from annotation data
	 * @return DbRefMeta
	 */
	protected function _createMeta()
	{
		$data = [];
		foreach (['class', 'updatable'] as $key => $name)
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
			if (isset($this->$name))
			{
				$data[$name] = $this->$name;
			}
		}
		return new DbRefMeta($data);
	}



}
