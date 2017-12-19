<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Related;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoId;

/**
 * RelatedType
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RelatedType implements AnnotatedInterface
{

	const TypeImage = 1;
	const TypeText = 2;
	const TypeSound = 3;

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @Label('Database id')
	 * @var MongoId
	 */
	public $_id = null;
	public $name = '';

	/**
	 * @var int
	 */
	public $type = self::TypeImage;

}
