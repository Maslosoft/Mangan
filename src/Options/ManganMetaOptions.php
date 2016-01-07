<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Options;

use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\Mangan\Annotations\Indexes\IndexAnnotation;
use Maslosoft\Mangan\Annotations\MetaOptionsHelper;
use Maslosoft\Mangan\Annotations\Validators\ValidatorAnnotation;
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
	 * Namespaces for annotations
	 * @var string[]
	 */
	public $namespaces = [
		MetaOptionsHelper::Ns,
		ValidatorAnnotation::Ns,
		IndexAnnotation::Ns
	];

}
