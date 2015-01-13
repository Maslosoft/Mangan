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

use Maslosoft\Mangan\Decorators\DbRefArrayDecorator;

/**
 * DB reference array annotation
 * @template DbRefArray(${class}, ${updatable})
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefArrayAnnotation extends DbRefAnnotation
{

	public function init()
	{
		$refMeta = $this->_createMeta();
		$refMeta->single = false;
		$refMeta->isArray = true;
		$this->_entity->dbRef = $refMeta;
		$this->_entity->decorators[] = DbRefArrayDecorator::class;
	}

}
