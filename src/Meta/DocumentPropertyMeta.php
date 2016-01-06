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

namespace Maslosoft\Mangan\Meta;

use Maslosoft\Addendum\Collections\MetaProperty;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Transformers\DocumentArray;
use Maslosoft\Mangan\Transformers\JsonArray;

/**
 * DocumentPropertyMeta
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DocumentPropertyMeta extends MetaProperty
{

	/**
	 * Field label
	 * @var string
	 */
	public $label = '';

	/**
	 * Description
	 * @var string
	 */
	public $description = '';

	/**
	 * DB Ref metadata
	 * @var DbRefMeta
	 */
	public $dbRef = null;

	/**
	 * Embedded document meta data
	 * @var EmbeddedMeta
	 */
	public $embedded = null;

	/**
	 * Related document meta data
	 * @var RelatedMeta
	 */
	public $related = null;

	/**
	 * If document is owned set this to true.
	 *
	 * **NOTE**: This should not be set directly, but rather by annotations.
	 *
	 * @var bool
	 */
	public $owned = false;

	/**
	 * I18N metadata
	 * @var I18NMeta
	 */
	public $i18n = null;

	/**
	 * Decorators
	 * @var string[]
	 */
	public $decorators = [];

	/**
	 * Sanitizer short name or class name
	 * @var string
	 */
	public $sanitizer = null;

	/**
	 * Whenever sanitization should be performed for array of elements
	 * @var bool
	 */
	public $sanitizeArray = false;

	/**
	 * If field should be persistent, by default true
	 * @var bool
	 */
	public $persistent = true;

	/**
	 * Whenever attribute is safe for mass assignement
	 * @see EntityManager::setAttributes()
	 * @var boolean|null
	 */
	public $safe = null;

	/**
	 * Whenever property is read only
	 * @var bool
	 */
	public $readonly = false;

	/**
	 * Whenever property should be included when converting to Json
	 * @see JsonArray
	 * @var bool
	 */
	public $toJson = true;

	/**
	 * Whenever property should be included when converting from Json
	 * @see JsonArray
	 * @var bool
	 */
	public $fromJson = true;

	/**
	 * Whenever property should be included when converting to document array
	 * @see DocumentArray
	 * @var bool
	 */
	public $toArray = true;

	/**
	 * Whenever property should be included when converting from document array
	 * @see DocumentArray
	 * @var bool
	 */
	public $fromArray = true;

	/**
	 * Whenever events should be propagated to this property sub objects
	 * @var bool
	 */
	public $propagateEvents = false;

	/**
	 * Validators configuration
	 * @var ValidatorMeta[]
	 */
	public $validators = [];

}
