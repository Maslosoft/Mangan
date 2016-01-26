<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
 * Secret('sha1')
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
			$this->_entity->decorators[] = SecretDecorator::class;
		}
		$this->_entity->secret = new SecretMeta($data);
	}

}
