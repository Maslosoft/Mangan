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

namespace Maslosoft\Mangan\Traits\Model;

use Exception;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\RestoreEvent;
use Maslosoft\Mangan\Events\TrashEvent;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Interfaces\TrashInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Model\Trash;
use Maslosoft\Mangan\ScenarioManager;
use Maslosoft\Mangan\Validator;

/**
 * Uswe this trait to make model trashable
 *
 * @author Piotr
 */
trait TrashableTrait
{

	/**
	 * Move to trash. Validation will be performed
	 * before trashing with `trash` (TrashInterface::ScenarioTrash) scenario.
	 *
	 *
	 * @see TrashInterface::ScenarioTrash
	 * @param boolean $runValidation whether to perform validation before saving the record.
	 * If the validation fails, the record will not be saved to database.
	 *
	 * @return boolean
	 * @Ignored
	 */
	public function trash($runValidation = true)
	{
		ScenarioManager::setScenario($this, TrashInterface::ScenarioTrash);
		$validator = new Validator($this);
		if (!$runValidation || $validator->validate())
		{
			$eventBefore = new TrashEvent($this);
			if (Event::hasHandler($this, TrashInterface::EventBeforeTrash))
			{
				if (!Event::valid($this, TrashInterface::EventBeforeTrash, $eventBefore))
				{
					return false;
				}
			}
			$meta = ManganMeta::create($this);

			$trash = $eventBefore->getTrash();
			if (empty($trash))
			{
				$trash = new Trash();
			}
			$trash->name = (string) $this;
			$trash->data = $this;
			$trash->type = isset($meta->type()->label) ? $meta->type()->label : get_class($this);
			if (!$trash->save())
			{
				return false;
			}

			$eventAfter = new TrashEvent($this);
			$eventAfter->setTrash($trash);

			if (!Event::valid($this, TrashInterface::EventAfterTrash, $eventAfter))
			{
				return false;
			}

			// Use deleteOne, to avoid beforeDelete event,
			// which should be raised only when really removing document:
			// when emtying trash

			$em = new EntityManager($this);
			return $em->deleteOne(PkManager::prepareFromModel($this));
		}
		return false;
	}

	/**
	 * Restore trashed item
	 * @return boolean
	 * @throws Exception
	 * @Ignored
	 */
	public function restore()
	{
		if (!$this instanceof TrashInterface)
		{
			// When trying to restore normal document instead of trash item
			throw new Exception(sprintf('Restore can be performed only on `%s` instance', TrashInterface::class));
		}
		$em = new EntityManager($this->data);

		// Set scenario to `restore` for model, which is just about to be restored
		ScenarioManager::setScenario($this->data, TrashInterface::ScenarioRestore);

		if (!Event::valid($this->data, TrashInterface::EventBeforeRestore))
		{
			return false;
		}

		$saved = $em->save();
		if (!$saved)
		{
			return false;
		}
		$finder = new Finder($this->data);
		$model = $finder->find(PkManager::prepareFromModel($this->data));
		if (empty($model))
		{
			return false;
		}
		$eventAfter = new RestoreEvent();
		$eventAfter->setTrashed($this->data);
		$eventAfter->setTrash($this);
		if (!Event::valid($model, TrashInterface::EventAfterRestore, $eventAfter))
		{
			return false;
		}

		$trashEm = new EntityManager($this);

		$this->data = null;

		// Use deleteOne, to avoid beforeDelete event,
		// which should be raised only when really removing document:
		// when emptying trash
		return $trashEm->deleteOne(PkManager::prepareFromModel($this));
	}

	abstract public function __toString();
}
