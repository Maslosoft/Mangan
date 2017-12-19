<?php

namespace Maslosoft\ManganTest\Extensions;

use Codeception\Event\TestEvent;
use Codeception\Extension;
use Maslosoft\EmbeDi\EmbeDi;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Interfaces\EventHandlersInterface;
use Maslosoft\Mangan\Mangan;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DestroyableEvent extends Event
{

	public static function destroyAllEvents()
	{
		parent::destroyEvents();
	}

}

/**
 * EventDestroyer
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EventDestroyer extends Extension
{

	// list events to listen to
	public static $events = [
		'test.before' => 'testBefore',
	];

	public function testBefore(TestEvent $e)
	{
		DestroyableEvent::destroyAllEvents();
		$m = Mangan::fly();
		$di = EmbeDi::fly($m->connectionId);
		foreach ($m->eventHandlers as $config)
		{
			$eh = $di->apply($config);
			assert($eh instanceof EventHandlersInterface);
			$eh->setupHandlers();
		}
	}

}
