<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Meta;

use Maslosoft\Mangan\Annotations\RelatedOrderingAnnotation;
use Maslosoft\Mangan\Interfaces\SortInterface;

/**
 * Related metadata holder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RelatedMeta extends BaseMeta
{

	/**
	 * Whether treat field as single related document
	 * @var bool
	 */
	public $single = false;

	/**
	 * Whether field should contain array of related documents.
	 * @var bool
	 */
	public $isArray = false;

	/**
	 * Default class for related document. Defaults to current document class.
	 * @var string
	 */
	public $class = '';

	/**
	 * Whether related objects should be updated on save of main document
	 * @var bool
	 */
	public $updatable = true;

	/**
	 * On which keys join documents.
	 *
	 * Keys should be current document field names, values should be related document field names.
	 *
	 * Simple relation:
	 * ```php
	 * [
	 * 		_id => parentId
	 * ]
	 * ```
	 *
	 * Complex relation:
	 * ```php
	 * [
	 * 		ownerId => parentId,
	 * 		companyId => companyId
	 * ]
	 * 	```
	 * @var mixed[]
	 */
	public $join = [];

	/**
	 * Default order of related entities.
	 * Key is sort field, value is direction, one of SortInterface constants.
	 *
	 * @see SortInterface
	 * @var int[]
	 */
	public $sort = [];

	/**
	 * Field for storing order
	 *
	 * @see RelatedOrderingAnnotation
	 * @var string
	 */
	public $orderField = '';

}
