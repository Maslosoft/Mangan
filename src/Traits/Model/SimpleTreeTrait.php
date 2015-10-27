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

namespace Maslosoft\Mangan\Traits\Model;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\RawFinder;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Interfaces\SimpleTreeInterface;
use Maslosoft\Mangan\Interfaces\TrashInterface;
use MongoId;

/**
 * TreeTrait
 * TODO Simple tree needs serious refactor
 * @see SimpleTreeInterface
 * @author Piotr
 */
trait SimpleTreeTrait
{

	use WithParentTrait;

	/**
	 * @DbRefArray
	 * @RelatedArray(parentId)
	 * @var AnnotatedInterface[]
	 */
	public $children = null;

	/**
	 * @Label('Manual sort')
	 * @var int
	 */
	public $order = 1000000;

	/**
	 * NOTE: This must be called by class using this trait
	 * @Ignored
	 */
	public function initTree()
	{
		$loadItems = function()
		{
			if ($this->children == null && $this->parentId !== null)
			{
				$criteria = new Criteria();
				$criteria->parentId = $this->_id;
				$this->children = (new Finder($this))->withCursor(false)->findAll($criteria);
			}
		};
		$loadItems->bindTo($this);

		Event::on($this, FinderInterface::EventAfterFind, $loadItems);


		if ($this instanceof TrashInterface)
		{
			// Trash related events
			$onBeforeTrash = function($event)
			{
				$this->_onBeforeTrash($event);
			};
			$onBeforeTrash->bindTo($this);
			Event::on($this, TrashInterface::EventBeforeTrash, $onBeforeTrash);


			$onAfterTrash = function($event)
			{
				$this->_onAfterTrash($event);
			};
			$onAfterTrash->bindTo($this);
			Event::on($this, TrashInterface::EventAfterTrash, $onAfterTrash);


			$onAfterRestore = function($event)
			{
				$this->_onAfterRestore($event);
			};
			$onAfterRestore->bindTo($this);
			Event::on($this, TrashInterface::EventAfterRestore, $onAfterRestore);
		}
	}

	private function _onBeforeTrash(ModelEvent $event)
	{
		$event->handled = true;
	}

	/**
	 * @param ModelEvent $event
	 */
	private function _onAfterTrash(ModelEvent $event)
	{
		foreach ($event->sender->children as $child)
		{
			$child->trash();
		}
	}

	private function _onAfterRestore(ModelEvent $event)
	{
		// Root nodes does not have parentId
		if ($this->parentId)
		{
			// Put node to root if parent does not exists
			/**
			 * TODO Use exists here instead of raw finder.
			 * TODO investigate why rawfinder was used here.
			 */
			if (!(new RawFinder($this))->findByPk(new MongoId($this->parentId)))
			{
				$this->parentId = null;
				(new EntityManager($this))->update(['parentId']);
			}
		}
	}

	/**
	 * Move to a new parent
	 * @param string|MongoId $parentId
	 * @param string[]|MongoId[] $order
	 * @Ignored
	 */
	public function moveTo($parentId, $order = [])
	{
		$this->parentId = $parentId;
		(new EntityManager($this))->update(['parentId']);

		$i = 0;

		$node = new static;
		$em = new EntityManager($node);
		foreach ((array) $order as $id)
		{
			$node->_id = $id;
			$node->order = $i++;
			$em->update(['order']);
		}
	}

}
