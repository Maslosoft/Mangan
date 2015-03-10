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
 * Set custom entity manager class
 * @Target('class')
 * @template EntityManager(${entityManagerLiteral})
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EntityManagerAnnotation extends ManganTypeAnnotation
{

	public $value = null;

	public function init()
	{
		$this->_entity->entityManager = $this->value;
	}

}
