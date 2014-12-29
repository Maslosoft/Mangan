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
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Interfaces\ITrash;
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
		if (Event::hasHandler($this, ITrash::EventBeforeTrash))
		{
			$event = new ModelEvent($this);
			Event::trigger($this, ITrash::EventBeforeTrash, $event);
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

		Event::trigger($this, ITrash::EventAfterTrash);

		// Use deleteOne, to avoid beforeDelete event,
		// which should be raised only when really removing document:
		// when emtying trash
		$criteria = new Criteria();
		$criteria->addCond('_id', '==', new MongoId($this->id));

		$em = new EntityManager($this);
		$em->deleteOne($criteria);
	}

	/**
	 * Restore trashed item
	 * @return boolean
	 * @throws Exception
	 */
	public function restore()
	{
		if (!$this instanceof Trash)
		{
			// When trying to restore normal document instead of trash item
			throw new Exception('Restore can be performed only on Trash instance');
		}
		$em = new EntityManager($this->data);
		//$this->data->init();
		Event::trigger($this->data, ITrash::EventBeforeRestore);

		$em->save();
		$finder = new Finder($em);
		$model = $finder->findByPk(new MongoId($this->data->id));
		if(!$model)
		{
			return false;
		}
		Event::trigger($model, ITrash::EventAfterRestore);

		$trashEm = new EntityManager($this);
		// $this->delete();
		// Use deleteOne, to avoid beforeDelete event,
		// which should be raised only when really removing document:
		// when emtying trash
		$this->data = null;

		$criteria = new Criteria();
		$criteria->addCond('_id', '==', new MongoId($this->id));

		$trashEm->deleteOne($criteria);
		return true;
	}

}
