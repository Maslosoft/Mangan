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
use Maslosoft\Mangan\Helpers\IndexManager;
use Maslosoft\Mangan\Interfaces\EventHandlersInterface;
use Maslosoft\Mangan\Interfaces\FinderInterface;

class IndexManagerHandler implements EventHandlersInterface
{
	public function setupHandlers()
	{
		$on = [
			EntityManager::EventBeforeInsert,
			EntityManager::EventBeforeSave,
			EntityManager::EventBeforeUpdate,
			FinderInterface::EventBeforeFind,
			FinderInterface::EventBeforeCount,
			FinderInterface::EventBeforeExists,
		];
		$handler = [$this, 'handle'];
		foreach ($on as $name)
		{
			Event::on(AnnotatedInterface::class, $name, $handler);
		}
	}

	public function handle(ModelEvent $e)
	{
		$e->isValid = true;
		// Fast and ugly
		if($e->name === FinderInterface::EventBeforeFind)
		{
			$e->handled = true;
		}
		if($e->name === FinderInterface::EventBeforeExists)
		{
			$e->handled = true;
		}
		if($e->name === FinderInterface::EventBeforeCount)
		{
			$e->handled = true;
		}
		
		IndexManager::fly()->create($e->sender);
		return true;
	}
}