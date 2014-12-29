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

namespace Maslosoft\Mangan\Traits\Model;

use CModelEvent;
use Exception;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Models\Trash;
use MongoId;

/**
 * Uswe this trait to make model trashable
 *
 * @author Piotr
 */
trait TrashableTrait
{

	public function trash()
	{
		if ($this->hasEventHandler('onBeforeTrash'))
		{
			$event = new CModelEvent($this);
			$this->onBeforeTrash($event);
			if (!$event->handled)
			{
				return false;
			}
		}

		$trash = new Trash();
		$trash->name = (string) $this;
		$trash->data = $this;
		$trash->type = isset($this->meta->type()->label) ? $this->meta->type()->label : get_class($this);
		$trash->save();
		if ($this->hasEventHandler('onAfterTrash'))
		{
			$this->onAfterTrash(new CModelEvent($this));
		}

// Use deleteOne, to avoid beforeDelete event,
// which should be raised only when really removing document:
// when emtying trash
		$criteria = new Criteria();
		$criteria->addCond('_id', '==', new MongoId($this->id));
		$this->deleteOne($criteria);
	}

	/**
	 * Restore trashed item
	 */
	public function restore()
	{
		if (!$this instanceof Trash)
		{
			// When trying to restore normal document instead of trash item
			throw new Exception('Restore can be performed only on Trash instance');
		}
		$this->data->init();
		$this->data->onBeforeRestore(new CModelEvent($this));
		$this->data->save();
		$model = $this->data->findByPk(new MongoId($this->data->id));
		$model->onAfterRestore(new CModelEvent($this));

// $this->delete();
// Use deleteOne, to avoid beforeDelete event,
// which should be raised only when really removing document:
// when emtying trash
		$this->data = null;

		$this->deleteOne([
			'_id' => $this->id
		]);
	}

	public function onBeforeTrash($event)
	{
		$this->raiseEvent('onBeforeTrash', $event);
	}

	public function onAfterTrash($event)
	{
		$this->raiseEvent('onAfterTrash', $event);
	}

	public function onBeforeRestore($event)
	{
		$this->raiseEvent('onBeforeRestore', $event);
	}

	public function onAfterRestore($event)
	{
		$this->raiseEvent('onAfterRestore', $event);
	}

}
