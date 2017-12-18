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

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Interfaces\EventHandlersInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
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
//		$handler = function(ModelEvent $e)
//		{
//			echo $e;
//		};
		foreach ($on as $name)
		{
			Event::on(WithParentTrait::class, $name, $handler);
		}
	}

	public function handle(ModelEvent $e)
	{
		$e->isValid = true;
		/**
		 * TODO This breaks Event/ParentChildTrashable
		 */
		return;
		$model = $e->sender;
		$meta = ManganMeta::create($model);
		foreach ($meta->fields() as $name => $fieldMeta)
		{
			if ($model->$name instanceof AnnotatedInterface)
			{
				$this->maybeSetValue($model, $model->$name);
			}
			elseif (is_array($model->$name))
			{
				foreach ($model->$name as $subModel)
				{
					$this->maybeSetValue($model, $subModel);
				}
			}
		}
	}

	private function maybeSetValue($model, $subModel)
	{
		/**
		 * TODO Possibly does not handle recurent traits (deep)
		 */
		$traits = class_uses($subModel);
		if (in_array(WithParentTrait::class, $traits))
		{
			$subModel->parentId = $model->_id;
		}
	}

}
