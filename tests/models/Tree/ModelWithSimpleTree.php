<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Tree;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Mangan\Traits\Model\SimpleTreeTrait;
use MongoId;

/**
 * ModelWithSimpleTree
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithSimpleTree implements AnnotatedInterface
{

	use SimpleTreeTrait;

	/**
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId
	 */
	public $_id = null;

	/**
	 *
	 * @var string
	 */
	public $name = '';

	public function __construct($name = '', $children = [])
	{
		$this->initTree();
		$this->name = $name;
		$this->children = $children;
	}

}
