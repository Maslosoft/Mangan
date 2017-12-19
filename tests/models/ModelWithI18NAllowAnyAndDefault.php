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
 * ModelWithI18NAllowAnyAndDefault
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithI18NAllowAnyAndDefault implements ModelInterface, InternationalInterface
{

	use I18NAbleTrait;

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id;

	/**
	 * @I18N(true, true)
	 * @var string
	 */
	public $title = '';

	/**
	 * @I18N(allowAny = true, allowDefault = true)
	 * @var bool
	 */
	public $active = false;

	/**
	 * @I18N(allowAny = true, allowDefault = true)
	 * @var bool
	 */
	public $foo = 'bar';

	/**
	 * Not i18n
	 * @var type
	 */
	public $layout = 'default';
}
