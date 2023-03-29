<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models;

use Maslosoft\Mangan\Document;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * ModelWithAlias
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithAlias extends Document
{

	/**
	 * @Sanitizer('MongoObjectId')
	 * @var MongoId
	 */
	public $_id = null;

	/**
	 * @Alias('_id')
	 * @Persistent(false)
	 * @Sanitizer('MongoObjectId')
	 * @var string
	 */
	public $id = null;

}
