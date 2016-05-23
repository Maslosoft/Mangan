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

use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Mangan\Meta\ManganTypeAnnotation;
use UnexpectedValueException;

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
		if (!ClassChecker::exists($this->value))
		{
			throw new UnexpectedValueException(sprintf('Class `%s` not found on @EntityManager annotation, on model `%s`', $this->value, $this->name));
		}
		$this->_entity->entityManager = $this->value;
	}

}
