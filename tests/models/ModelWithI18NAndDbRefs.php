<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models;

use Maslosoft\Mangan\Interfaces\InternationalInterface;
use Maslosoft\Mangan\Interfaces\ModelInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Mangan\Traits\I18NAbleTrait;
use MongoId;

/**
 * ModelWithI18N
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithI18NAndDbRefs implements ModelInterface, InternationalInterface
{

	use I18NAbleTrait;

	/**
	 * @Maslosoft\Mangan\Annotations\Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id;

	/**
	 * @I18N
	 * @var string
	 */
	public $title = '';

	/**
	 * @DbRef(ModelWithI18N)
	 * @var ModelWithI18N
	 */
	public $one = null;

	/**
	 * @DbRefArray(ModelWithI18N)
	 * @var ModelWithI18N[]
	 */
	public $many = [];
}
