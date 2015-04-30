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

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Ilmatar\Components\MongoDocument;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Helpers\RawFinder;
use Maslosoft\Mangan\Interfaces\ISimpleTree;
use Maslosoft\Mangan\Interfaces\ITrash;
use MongoId;

/**
 * TreeTrait
 *
 * @see ISimpleTree
 * @author Piotr
 */
trait SimpleTreeTrait
{

	use WithParentTrait;

	/**
	 * @DbRefArray
	 * @var IAnnotated[]
	 */
	public $children = null;

	/**
	 * @Label('Manual sort')
	 * @var int
	 */
	public $order = 1000000;

	public function init()
	{
		/**
		 * TODO This propably should be initialized somewhere else
		 */
		$onBeforeTrash = function($event)
		{
			$this->_onBeforeTrash($event);
		};
		$onBeforeTrash->bindTo($this);
		Event::on($this, ITrash::EventBeforeTrash, $onBeforeTrash);


		$onAfterTrash = function($event)
		{
			$this->_onAfterTrash($event);
		};
		$onAfterTrash->bindTo($this);
		Event::on($this, ITrash::EventAfterTrash, $onAfterTrash);


		$onAfterRestore = function($event)
		{
			$this->_onAfterRestore($event);
		};
		$onAfterRestore->bindTo($this);
		Event::on($this, ITrash::EventAfterRestore, $onAfterRestore);
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
			if (!(new RawFinder($this))->findByPk(new MongoId($this->parentId)))
			{
				$this->parentId = null;
				(new EntityManager($this))->update(['parentId']);
			}
		}
	}

	/**
	 * TODO This MUST be binded only if explicitly requested
	 * TODO This should be handled by `DbRef` annotation
	 * @return MongoDocument[]
	 */
	public function getChildren($full = false)
	{
		$children = $this->getAttribute('children');
		if (null === $children)
		{
			if (!$this->id)
			{
				return [];
			}
			$criteria = new Criteria();
			$criteria->addCond('parentId', '==', new MongoId((string) $this->id));
			$criteria->sort('order', Criteria::SORT_ASC);
			if (!$full)
			{
				$criteria->select([
					'_id',
					'parentId',
					'title',
					'order'
				]);
			}
			$children = $this->findAll($criteria);

			$this->setAttribute('children', $children);
		}
		return $children;
	}

	public function setChildren()
	{
		
	}

	/**
	 * Move to a new parent
	 * @param string|MongoId $parentId
	 * @param string[]|MongoId[] $order
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
