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
 * RelatedStats
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RelatedStats implements AnnotatedInterface
{

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @Label('Database id')
	 * @var MongoId
	 */
	public $_id = null;
	public $name = '';

	/**
	 * @Sanitizer(MongoObjectId, nullable = true)
	 * @var MongoId
	 */
	public $ownerId = null;

}
