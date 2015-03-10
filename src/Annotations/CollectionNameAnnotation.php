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

use Maslosoft\Mangan\Meta\ManganTypeAnnotation;

/**
 * CollectionName
 * Use this annotation to set custom collection name
 * @Target('class')
 * @template CollectionName('${name}')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class CollectionNameAnnotation extends ManganTypeAnnotation
{

	public $value;

	public function init()
	{
		$this->_entity->collectionName = $this->value;
	}

}
