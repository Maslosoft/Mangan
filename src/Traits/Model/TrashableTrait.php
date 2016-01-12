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

use Exception;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Interfaces\TrashInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Models\Trash;

/**
 * Uswe this trait to make model trashable
 *
 * @author Piotr
 */
trait TrashableTrait
{

	/**
	 * Move to trash
	 * @return boolean
	 * @Ignored
	 */
	public function trash()
	{
		if (Event::hasHandler($this, TrashInterface::EventBeforeTrash))
		{
			$event = new ModelEvent($this);
			Event::trigger($this, TrashInterface::EventBeforeTrash, $event);
			if (!$event->handled)
			{
				return false;
			}
		}
		$meta = ManganMeta::create($this);

		$trash = new Trash();
		$trash->name = (string) $this;
		$trash->data = $this;
		$trash->type = isset($meta->type()->label) ? $meta->type()->label : get_class($this);
		$trash->save();

		Event::trigger($this, TrashInterface::EventAfterTrash);

		// Use deleteOne, to avoid beforeDelete event,
		// which should be raised only when really removing document:
		// when emtying trash

		$em = new EntityManager($this);
		$em->deleteOne(PkManager::prepareFromModel($this));
	}

	/**
	 * Restore trashed item
	 * @return boolean
	 * @throws Exception
	 * @Ignored
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
		Event::trigger($this->data, TrashInterface::EventBeforeRestore);

		$em->save();
		$finder = new Finder($this->data);
		$model = $finder->find(PkManager::prepareFromModel($this->data));
		if (!$model)
		{
			return false;
		}
		Event::trigger($model, TrashInterface::EventAfterRestore);

		$trashEm = new EntityManager($this);
		// $this->delete();
		// Use deleteOne, to avoid beforeDelete event,
		// which should be raised only when really removing document:
		// when emtying trash
		$this->data = null;

		$trashEm->deleteOne(PkManager::prepareFromModel($this));
		return true;
	}

}
