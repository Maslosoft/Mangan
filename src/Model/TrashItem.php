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

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Interfaces\TrashInterface;

/**
 * TrashItem
 *
 * @Label('Trashed item')
 * @author Piotr
 */
class TrashItem extends Document implements TrashInterface
{

	/**
	 * Element name
	 * @Label('Name')
	 * @var string
	 */
	public $name = '';

	/**
	 * Type of trashed item
	 * @Label('Type')
	 * @var string
	 */
	public $type = '';

	public function getCollectionName()
	{
		return 'Mangan.Trash';
	}

}
