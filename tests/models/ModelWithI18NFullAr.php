<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models;

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoId;

/**
 * ModelWithI18N
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithI18NFullAr extends Document
{

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id;

	/**
	 * @I18N
	 * @Label('Title')
	 * @var string
	 */
	public $title = '';

	/**
	 * @I18N
	 * @var bool
	 */
	public $active = false;

	/**
	 * @I18N
	 * @Label('Foo name')
	 * @var bool
	 */
	public $foo = 'bar';

	/**
	 * Not i18n
	 * @var type
	 */
	public $layout = 'default';

}
