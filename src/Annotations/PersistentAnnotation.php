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

use Maslosoft\Mangan\Decorators\PersistentDecorator;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;

/**
 * If true, value should be saved in database.
 * By default all public properties are stored into db, so use it only when
 * property should not be stored
 * @Target('property')
 * @template Persistent(${false})
 * @author Piotr
 */
class PersistentAnnotation extends ManganPropertyAnnotation
{

	public $value = true;

	public function init()
	{
		$this->getEntity()->persistent = (bool) $this->value;
		$this->getEntity()->decorators[] = PersistentDecorator::class;
	}

}
