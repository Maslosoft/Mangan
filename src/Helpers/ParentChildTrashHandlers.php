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

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Events\RestoreEvent;
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;
use Maslosoft\Mangan\Interfaces\TrashInterface;
use UnexpectedValueException;

/**
 * OwneredTrashHandlers
 * Use this class to create trash handlers for ownered items.
 *
 * This class provides event handlers to properly manage trash, however it is
 * optional, so ownered and trashable can be handled by some custom methods.
 * These handles are not automatically registered.
 *
 * NOTE: Register **only once per type**, or it will not work properly.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ParentChildTrashHandlers
{

	/**
	 * Register event handlers for parent of parent-child relation.
	 *
	 * @param AnnotatedInterface $parent
	 * @param string $childClass
	 */
	public function registerParent(AnnotatedInterface $parent, $childClass)
	{
		if (!ClassChecker::exists($childClass))
		{
			throw new UnexpectedValueException(sprintf('Class `%s` not found', $childClass));
		}
		// Delete all of this child items after removing from trash
		$beforeDelete = function(ModelEvent $event) use($parent, $childClass)
		{
			$model = $event->sender;
			$event->isValid = true;
			if ($model instanceof $parent)
			{
				$child = new $childClass;
				$criteria = new Criteria(null, $child);
				$criteria->parentId = $model->_id;
				$event->isValid = $child->deleteAll($criteria);
			}
		};
		Event::on($parent, EntityManagerInterface::EventBeforeDelete, $beforeDelete);

		// Trash all child items from parent item
		$afterTrash = function(ModelEvent $event)use($parent, $childClass)
		{
			$model = $event->sender;
			$event->isValid = true;
			if ($model instanceof $parent)
			{
				$child = new $childClass;
				$criteria = new Criteria(null, $child);
				$criteria->parentId = $model->_id;

				$items = $child->findAll($criteria);

				// No items found, so skip
				if (empty($items))
				{
					$event->isValid = true;
					return true;
				}

				// Trash in loop all items
				foreach ($items as $item)
				{
					if (!$item->trash())
					{
						$event->isValid = false;
						return false;
					}
				}
			}
		};

		Event::on($parent, TrashInterface::EventAfterTrash, $afterTrash);

		// Restore all child items from parent, but only those after it was trashed.
		// This will keep previously trashed items in trash
		$afterRestore = function(RestoreEvent $event)use($parent, $childClass)
		{
			$model = $event->sender;
			if ($model instanceof $parent)
			{
				$child = new $childClass;
				$trash = $event->getTrash();
				$criteria = new Criteria(null, $trash);

				// Conditions decorator do not work with dots so sanitize manually.
				$s = new Sanitizer($child);
				$id = $s->write('parentId', $model->_id);
				$criteria->addCond('data.parentId', '==', $id);

				// Restore only child items trashed when blog was trashed - skip earlier
				$criteria->addCond('createDate', 'gte', $trash->createDate);

				$trashedItems = $trash->findAll($criteria);
				if (empty($trashedItems))
				{
					$event->isValid = true;
					return true;
				}

				// Restore all items
				foreach ($trashedItems as $trashedItem)
				{
					$trashedItem->restore();
				}
			}
			$event->isValid = true;
		};

		Event::on($parent, TrashInterface::EventAfterRestore, $afterRestore);
	}

	/**
	 * Register event handlers for child item of parent-child relation.
	 *
	 * @param AnnotatedInterface $child
	 * @param string $parentClass
	 * @throws UnexpectedValueException
	 */
	public function registerChild(AnnotatedInterface $child, $parentClass)
	{
		if (!ClassChecker::exists($parentClass))
		{
			throw new UnexpectedValueException(sprintf('Class `%s` not found', $parentClass));
		}
		// Prevent restoring item if parent does not exists
		$beforeRestore = function(ModelEvent $event)use($child, $parentClass)
		{
			$model = $event->sender;

			if ($model instanceof $child)
			{
				$parent = new $parentClass;
				$criteria = new Criteria(null, $parent);
				$criteria->_id = $model->parentId;
				if (!$parent->exists($criteria))
				{
					$event->isValid = false;
					return false;
				}
			}
			$event->isValid = true;
		};
		Event::on($child, TrashInterface::EventBeforeRestore, $beforeRestore);
	}

}
