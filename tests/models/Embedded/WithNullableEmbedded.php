<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Embedded;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\ManganTest\Models\Plain\SimplePlainEmbedded;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * WithPlainEmbedded
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class WithNullableEmbedded implements AnnotatedInterface
{

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id;

	/**
	 * @Embedded(SimplePlainEmbedded, 'nullable' => true)
	 * @var SimplePlainEmbedded
	 */
	public $stats = null;
	public $title = '';

}
