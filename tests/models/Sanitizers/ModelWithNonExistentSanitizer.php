<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Sanitizers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * ModelWithNonExistentSanitizer
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithNonExistentSanitizer implements AnnotatedInterface
{

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id = null;

	/**
	 * @Sanitizer(CustomSanitizerWhichInFactDoesNotExistsOrIsNotImported)
	 */
	public $title = null;

}
