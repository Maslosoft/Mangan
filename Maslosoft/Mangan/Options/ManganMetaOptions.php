<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Options;

use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\Mangan\Meta\DocumentMethodMeta;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;

/**
 * MetaOptions
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ManganMetaOptions extends MetaOptions
{

	/**
	 * Meta container class name for type (class)
	 * @var string
	 */
	public $typeClass = DocumentTypeMeta::class;

	/**
	 * Meta container class name for method
	 * @var string
	 */
	public $methodClass = DocumentMethodMeta::class;

	/**
	 * Meta container class name for property
	 * @var string
	 */
	public $propertyClass = DocumentPropertyMeta::class;

	/**
	 * Default namespace for annotations
	 * @var string
	 */
	public $annotationsNamespace = '';

}
