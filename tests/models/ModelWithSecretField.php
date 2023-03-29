<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\ManganTest\Extensions\Hasher;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * ModelWithSecretField
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithSecretField implements AnnotatedInterface
{

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id = null;

	/**
	 * @Secret
	 * @var string
	 */
	public $password = '';

	/**
	 * @Secret('sha1')
	 * @var string
	 */
	public $hash = '';

	/**
	 * @Secret({Hasher, 'hash'})
	 * @see Hasher
	 * @var string
	 */
	public $activationKey = '';

}
