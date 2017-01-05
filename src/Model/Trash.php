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

namespace Maslosoft\Mangan\Model;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * Trash
 *
 * @author Piotr
 * @Label('Trashed item with content')
 */
class Trash extends TrashItem
{

	/**
	 * Trashed document
	 * @Embedded()
	 * @KoBindable(false)
	 * @var AnnotatedInterface
	 */
	public $data = null;

}
