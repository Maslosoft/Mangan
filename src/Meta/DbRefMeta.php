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
	public $updatable = false;

}
