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
