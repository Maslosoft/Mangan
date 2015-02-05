<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Meta;

use Maslosoft\Addendum\Collections\MetaProperty;
use Maslosoft\Mangan\Sanitizers\ISanitizer;
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
	 * Embedded document default class
	 * @var string|bool
	 */
	public $embedded = null;

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
	 * Sanitizer
	 * @var ISanitizer
	 */
	public $sanitizer = null;

	/**
	 * If field should be persistent, by default true
	 * @var bool
	 */
	public $persistent = true;

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
	 * Whenever property should be included when converting to document array
	 * @see DocumentArray
	 * @var bool
	 */
	public $toArray = true;

}
