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

/**
 * Embedded metadata holder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmbeddedMeta extends BaseMeta
{

	/**
	 * Whenever treat field as single referenced document
	 * @var bool
	 */
	public $single = false;

	/**
	 * Whenever field should contain array of referenced documents.
	 * @var bool
	 */
	public $isArray = false;

	/**
	 * Comparing key. This is used to update db ref instances from external sources.
	 * This is only usefull in embedded arrays.
	 * @var string|array
	 */
	public $key = null;

	/**
	 * Default class for embedded documents, or doucment arrays.
	 * @var string
	 */
	public $class = null;

}
