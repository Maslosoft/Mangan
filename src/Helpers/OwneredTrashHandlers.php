<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * OwneredTrashHandlers
 * Use this class to create trash handlers for ownered items.
 *
 * This class provides event handlers to properly manage trash, however it is
 * optional, so ownered and trashable can be handled by some custom methods.
 *
 * NOTE: Register **only once per type**, or it will not work properly.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class OwneredTrashHandlers
{

	private $parent = null;
	private $child = null;

	public function __construct(AnnotatedInterface $parentModel, AnnotatedInterface $childModel)
	{
		$this->parent = $parentModel;
		$this->child = $childModel;
	}

	/**
	 * Register event handlers for parent of parent-child relation.
	 */
	public function registerParent()
	{
		// Delete all of this child items after removing from trash
		$beforeDelete = function(ModelEvent $event)
		{
			$model = $event->sender;
			if ($model instanceof $this->parent)
			{
				$criteria = new Criteria(null, $this->child);
				$criteria->parentId = $model->id;
				$this->child->deleteAll($criteria);
			}
			$event->isValid = true;
		};
		Event::on($this->parent, EntityManagerInterface::EventBeforeDelete, $beforeDelete);

		// Trash all child items from parent item
		$afterTrash = function(ModelEvent $event)
		{
			$model = $event->sender;
			if ($model instanceof $this->parent)
			{
				$criteria = new Criteria(null, $this->child);
				$criteria->parentId = $model->id;

				$items = $this->child->findAll($criteria);

				// No items found, so skip
				if (empty($items))
				{
					$event->isValid = true;
					return true;
				}

				// Trash in loop all items
				foreach ($items as $item)
				{
					$item->trash();
				}
			}
			$event->isValid = true;
		};

		Event::on($this->parent, TrashInterface::EventAfterTrash, $afterTrash);

		// Restore all child items from parent, but only those after it was trashed.
		// This will keep previously trashed items in trash
		$afterRestore = function(RestoreEvent $event)
		{
			$model = $event->sender;
			if ($model instanceof $this->parent)
			{
				$trash = $event->getTrashed();
				$criteria = new Criteria(null, $trash);

				// Conditions decorator do not work with dots so sanitize manually.
				$s = new Sanitizer($this->child);
				$id = $s->write('parentId', $model->id);
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

		Event::on($this->parent, TrashInterface::EventAfterRestore, $afterRestore);
	}

	/**
	 * Register event handlers for child item of parent-child relation.
	 */
	public function registerChild()
	{
		// Prevent restoring item if parent does not exists
		$beforeRestore = function(ModelEvent $event)
		{
			$model = $event->sender;

			if ($model instanceof $this->child)
			{
				$criteria = new Criteria(null, $this->parent);
				$criteria->_id = $model->parentId;
				if (!$this->parent->exists($criteria))
				{
					$event->isValid = false;
					return false;
				}
			}
			$event->isValid = true;
		};
		Event::on($this->child, TrashInterface::EventBeforeRestore, $beforeRestore);
	}

}
