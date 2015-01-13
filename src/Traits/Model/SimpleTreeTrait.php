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

namespace Maslosoft\Models\Traits;

use CEvent;
use Maslosoft\Ilmatar\Components\MongoDocument;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Interfaces\ISimpleTree;
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
	 * @KoBindable(true)
	 * Embedded array is used here to properly populate nested children
	 * In fact it is embedded array but not stored in db
	 * @EmbeddedArray
	 * @Persistent(false);
	 * @var type
	 */
	public $children = null;

	/**
	 * @Label('Manual sort')
	 * @var int
	 */
	public $order = 1000000;

	public function init()
	{
		parent::init();
		return;
		if ($this->hasEvent('onBeforeTrash'))
		{
			$onBeforeTrash = function($event)
			{
				$this->_onBeforeTrash($event);
			};
			$onBeforeTrash->bindTo($this);
			$this->onBeforeTrash = $onBeforeTrash;
		}
		if ($this->hasEvent('onAfterTrash'))
		{
			$onAfterTrash = function($event)
			{
				$this->_onAfterTrash($event);
			};
			$onAfterTrash->bindTo($this);
			$this->onAfterTrash = $onAfterTrash;
		}
		if ($this->hasEvent('onBeforeRestore'))
		{
			$onBeforeRestore = function($event)
			{

			};
			$onBeforeRestore->bindTo($this);
			$this->onBeforeRestore = $onBeforeRestore;
		}
		if ($this->hasEvent('onAfterRestore'))
		{
			$onAfterRestore = function($event)
			{
				$this->_onAfterRestore($event);
			};
			$onAfterRestore->bindTo($this);
			$this->onAfterRestore = $onAfterRestore;
		}
	}

	private function _onBeforeTrash($event)
	{
		$event->handled = true;
	}

	/**
	 * @param CEvent $event
	 */
	private function _onAfterTrash($event)
	{
		foreach ($event->sender->children as $child)
		{
			$child->trash();
		}
	}

	private function _onAfterRestore($event)
	{
		// Root nodes does not have parentId
		if ($this->parentId)
		{
			if (!$this->findByPk(new MongoId($this->parentId)))
			{
				$this->parentId = null;
				$this->update(['parentId'], true);
			}
		}
	}

	/**
	 * TODO This MUST be binded only if explicitly requested
	 * TODO This should be handled by `Related` annotation
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
		(new EntityManager($this))->update(['parentId'], true);
		$this->update(['parentId'], true);
		$i = 0;

		$node = new self;
		$em = new EntityManager($node);
		foreach ((array) $order as $id)
		{
			$node->_id = $id;
			$node->order = $i++;
			$em->update(['order'], true);
		}
	}

}
