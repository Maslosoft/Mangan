<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models;

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Sanitizers\StringSanitizer;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * ModelWithArrayField
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithArrayField extends Document
{

	/**
	 * @Sanitizer('MongoObjectId')
	 * @var MongoId
	 */
	public $_id = null;

	/**
	 * @SanitizerArray(StringSanitizer)
	 *
	 * @see StringSanitizer
	 * @var string
	 */
	public $tags = [];

}
