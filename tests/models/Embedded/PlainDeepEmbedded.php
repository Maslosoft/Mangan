<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Embedded;

use Maslosoft\Mangan\Interfaces\ModelInterface;
use MongoId;

/**
 * PlainDeepEmbedded
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PlainDeepEmbedded implements ModelInterface
{

	/**
	 * @Sanitizer('MongoObjectId')
	 * @var MongoId
	 */
	public $_id = '';

	/**
	 *
	 * @var string
	 */
	public $title = '';

	/**
	 * @Embedded(WithPlainEmbedded)
	 * @var WithPlainEmbedded
	 */
	public $withPlain = null;

	/**
	 * @EmbeddedArray(WithPlainEmbedded)
	 * @var WithPlainEmbedded
	 */
	public $withPlainArray = [];

}
