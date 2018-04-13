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

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Events\RestoreEvent;
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;
use Maslosoft\Mangan\Interfaces\OwneredInterface;
use Maslosoft\Mangan\Interfaces\TrashInterface;
use UnexpectedValueException;

/**
 * ParentChildTrashHandlers
 * Use this class to create trash handlers for owned items.
 *
 * This class provides event handlers to properly manage trash, however it is
 * optional, so owned and trashable can be handled by some custom methods.
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
	 * @param AnnotatedInterface|string $parent
	 * @param string $childClass
	 */
	public function registerParent($parent, $childClass)
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

			if (is_a($model, $parent))
			{
				$child = new $childClass;
				// Ensure owner
				if($child instanceof OwneredInterface)
				{
					$child->setOwner($model);
				}
				$criteria = new Criteria(null, $child);
				$criteria->parentId = $this->getPk($model);

				$event->isValid = $child->deleteAll($criteria);
			}
			return $event->isValid;
		};
		$beforeDelete->bindTo($this);
		Event::on($parent, EntityManagerInterface::EventBeforeDelete, $beforeDelete);

		// Trash all child items from parent item
		$afterTrash = function(ModelEvent $event)use($parent, $childClass)
		{
			$model = $event->sender;
			$event->isValid = true;
			if (is_a($model, $parent))
			{
				$child = new $childClass;
				// Ensure owner
				if($child instanceof OwneredInterface)
				{
					$child->setOwner($model);
				}
				$criteria = new Criteria(null, $child);
				$criteria->parentId = $this->getPk($model);

				$items = $child->findAll($criteria);

				// No items found, so skip
				if (empty($items))
				{
					$event->isValid = true;
					return $event->isValid;
				}

				// Trash in loop all items
				foreach ($items as $item)
				{
					// Ensure owner
					if($item instanceof OwneredInterface)
					{
						$item->setOwner($model);
					}
					if (!$item->trash())
					{
						$event->isValid = false;
						return $event->isValid;
					}
				}
			}
			return $event->isValid;
		};
		$afterTrash->bindTo($this);
		Event::on($parent, TrashInterface::EventAfterTrash, $afterTrash);

		// Restore all child items from parent, but only those after it was trashed.
		// This will keep previously trashed items in trash
		$afterRestore = function(RestoreEvent $event)use($parent, $childClass)
		{
			$model = $event->sender;
			if (is_a($model, $parent))
			{
				$child = new $childClass;
				// Ensure owner
				if($child instanceof OwneredInterface)
				{
					$child->setOwner($model);
				}
				$trash = $event->getTrash();
				$criteria = new Criteria(null, $trash);

				// Conditions decorator do not work with dots so sanitize manually.
				$s = new Sanitizer($child);

				$id = $s->write('parentId', $this->getPk($model));
				$criteria->addCond('data.parentId', '==', $id);

				// Restore only child items trashed when parent was trashed.
				// Skip earlier items
				assert(isset($trash->createDate), sprintf('When implementing `%s`, `createDate` field is required and must be set to date of deletion', TrashInterface::class));
				$criteria->addCond('createDate', 'gte', $trash->createDate);

				$trashedItems = $trash->findAll($criteria);
				if (empty($trashedItems))
				{
					$event->isValid = true;
					return $event->isValid;
				}

				// Restore all items
				$restored = [];
				foreach ($trashedItems as $trashedItem)
				{
					// Ensure owner
					if($trashedItem instanceof OwneredInterface)
					{
						$trashedItem->setOwner($model);
					}
					$restored[] = (int) $trashedItem->restore();
				}
				if(array_sum($restored) !== count($restored))
				{
					$event->isValid = false;
					return $event->isValid;
				}
			}
			$event->isValid = true;
			return $event->isValid;
		};
		$afterRestore->bindTo($this);
		Event::on($parent, TrashInterface::EventAfterRestore, $afterRestore);
	}

	/**
	 * Register event handlers for child item of parent-child relation.
	 *
	 * @param AnnotatedInterface|string $child
	 * @param string $parentClass
	 * @throws UnexpectedValueException
	 */
	public function registerChild($child, $parentClass)
	{
		assert(ClassChecker::exists($parentClass), new UnexpectedValueException(sprintf('Class `%s` not found', $parentClass)));

		// Prevent restoring item if parent does not exists
		$beforeRestore = function(ModelEvent $event)use($child, $parentClass)
		{
			$model = $event->sender;

			if (is_a($model, $child))
			{
				$parent = new $parentClass;
				$criteria = new Criteria(null, $parent);
				assert(isset($model->parentId));
				$criteria->_id = $model->parentId;
				if (!$parent->exists($criteria))
				{
					$event->isValid = false;
					return $event->isValid;
				}
			}
			$event->isValid = true;
			return $event->isValid;
		};
		Event::on($child, TrashInterface::EventBeforeRestore, $beforeRestore);
	}

	private function getPk(AnnotatedInterface $model)
	{
		$pk = PkManager::getFromModel($model);
		assert(!is_array($pk), 'Composite PK of `%s` not allowed for parentId');
		return $pk;
	}

}
