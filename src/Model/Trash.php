<?php

/**
 * This SOFTWARE PRODUCT is protected by copyright laws and international copyright treaties,
 * as well as other intellectual property laws and treaties.
 * This SOFTWARE PRODUCT is licensed, not sold.
 * For full licence agreement see enclosed LICENCE.html file.
 *
 * @licence LICENCE.html
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @link http://maslosoft.com/
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
