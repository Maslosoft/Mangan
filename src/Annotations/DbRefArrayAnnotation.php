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

use Maslosoft\Mangan\Decorators\DbRefArrayDecorator;

/**
 * DB reference array annotation
 * @template DbRefArray(${class}, ${updatable})
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefArrayAnnotation extends DbRefAnnotation
{

	public $value = [];

	/**
	 * Comparing key. This is used to update db ref instances from external sources.
	 * @var string|array
	 */
	public $key = null;

	public function init()
	{
		$refMeta = $this->_getMeta();
		$refMeta->key = $this->key;
		$refMeta->single = false;
		$refMeta->isArray = true;
		$this->_entity->dbRef = $refMeta;
		$this->_entity->propagateEvents = true;
		$this->_entity->decorators[] = DbRefArrayDecorator::class;
	}

}
