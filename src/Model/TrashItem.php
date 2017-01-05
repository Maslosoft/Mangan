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

use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Interfaces\TrashInterface;
use Maslosoft\Mangan\Sanitizers\DateSanitizer;
use Maslosoft\Mangan\Traits\Model\TrashableTrait;
use MongoDate;

/**
 * TrashItem
 *
 * @Label('Trashed item')
 * @author Piotr
 */
class TrashItem extends Document implements TrashInterface
{

	use TrashableTrait;

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

	/**
	 * When it was trashed.
	 * @Sanitizer(DateSanitizer)
	 * @see DateSanitizer
	 * @var MongoDate
	 */
	public $createDate = '';

	public function getCollectionName()
	{
		return 'Mangan.Trash';
	}

	/**
	 * Purge all items from trash. Returns number of removed items.
	 * @return int
	 */
	public function purge()
	{
		$removed = 0;
		// Remove with loop to fire before/afterDelete events
		foreach ($this->findAll() as $trash)
		{
			if ($trash->delete())
			{
				$removed++;
			}
		}
		return $removed;
	}

	public function __toString()
	{
		return 'Trashed';
	}

}
