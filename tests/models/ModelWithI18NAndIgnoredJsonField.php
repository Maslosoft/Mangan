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
use MongoId;

/**
 * ModelWithI18N
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithI18NAndIgnoredJsonField implements ModelInterface, InternationalInterface
{

	use \Maslosoft\Mangan\Traits\I18NAbleTrait;

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
	 * @I18N
	 * @var bool
	 */
	public $active = false;

	/**
	 * @I18N
	 * @var bool
	 */
	public $foo = 'bar';

	/**
	 *
	 * @ToJson(false)
	 * @FromJson(false)
	 * @var bool
	 */
	public $notJson = true;
}
