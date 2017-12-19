<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Sanitizers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\BooleanSanitizer;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoId;

/**
 * ModelWithNullableBoolean
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithNullableBoolean implements AnnotatedInterface
{

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id = null;

	/**
	 * @Sanitizer(BooleanSanitizer, nullable = true)
	 * @see BooleanSanitizer
	 * @var bool|null
	 */
	public $value = null;

}
