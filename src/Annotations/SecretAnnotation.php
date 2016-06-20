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

use Maslosoft\Mangan\Decorators\Property\SecretDecorator;
use Maslosoft\Mangan\Meta\ManganPropertyAnnotation;
use Maslosoft\Mangan\Meta\SecretMeta;

/**
 * Secret Annotation
 *
 * Use this annotation to create "write-only" field, with possible callback when saving.
 * When this annotation is active, only non empty values will be stored in database.
 *
 * Optional callback can be used to transform value before save, ie hash function:
 *
 * ```php
 * @Secret('sha1')
 * ```
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SecretAnnotation extends ManganPropertyAnnotation
{

	public $value;

	public function init()
	{
		$data = [];
		if ($this->value)
		{
			$data['callback'] = $this->value;
			$this->getEntity()->decorators[] = SecretDecorator::class;
		}
		$this->getEntity()->secret = new SecretMeta($data);
	}

}
