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

namespace Maslosoft\Mangan\Events\Handlers;

use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Helpers\CompositionIterator;
use Maslosoft\Mangan\Interfaces\EventHandlersInterface;
use Maslosoft\Mangan\Interfaces\TrashInterface;
use Maslosoft\Mangan\Traits\Model\WithParentTrait;

/**
 * ParentIdHandler
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ParentIdHandler implements EventHandlersInterface
{

	public function setupHandlers()
	{
		$on = [
			EntityManager::EventBeforeInsert,
			EntityManager::EventBeforeSave,
			EntityManager::EventBeforeUpdate,
		];
		$handler = [$this, 'handle'];

		foreach ($on as $name)
		{
			Event::on(WithParentTrait::class, $name, $handler);
		}
	}

	public function handle(ModelEvent $e)
	{
		$e->isValid = true;
		$model = $e->sender;

		// Don't act on trash, perhaps
		// should be made in different way
		if($model instanceof TrashInterface)
		{
			return $e->isValid;
		}

		$it = (new CompositionIterator($model))->direct();
		foreach ($it as $subModel)
		{
			$this->maybeSetValue($model, $subModel);
		}
		return $e->isValid;
	}

	private function maybeSetValue($model, $subModel)
	{
		if (property_exists($subModel, 'parentId'))
		{
			$subModel->parentId = $model->_id;
		}
	}

}
