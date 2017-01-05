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
 * DbRef metadata holder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefMeta extends BaseMeta
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
	 * Default class for referenced document
	 * @var string
	 */
	public $class = '';

	/**
	 * Whenever referenced objects should be updated on save of main document
	 * @var bool
	 */
	public $updatable = true;

	/**
	 * Comparing key. This is used to update db ref instances from external sources.
	 * This is only usefull in db ref arrays.
	 * @var string|array
	 */
	public $key = null;

}
