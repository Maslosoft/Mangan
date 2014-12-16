<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Events;

use SplObjectStorage;

/**
 * Event Manager, based on Yii 2 Component class
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EventDispatcher
{

	/**
	 *
	 * @var IEvent
	 */
	private $_events;

	/**
	 * Event storage
	 * @var SplObjectStorage
	 */
	public static $storage = null;

	protected function __construct($model)
	{
		$this->_model = $model;
		if(null === self::$storage)
		{
			self::$storage = new SplObjectStorage();
		}
		if(isset(self::$storage[$this->_model]))
		{
			$this->_events = self::$storage[$this->_model];
		}
		self::$storage[$this->_model] = 'blah';
	}

	public function dump()
	{
		var_dump(self::$storage[$this->_model]);
		var_dump(count(self::$storage));
	}

	public static function create($model)
	{
		return new static($model);
	}

	/**
	 * Attaches an event handler to an event.
	 *
	 * The event handler must be a valid PHP callback. The followings are
	 * some examples:
	 *
	 * ~~~
	 * function ($event) { ... } // anonymous function
	 * [$object, 'handleClick'] // $object->handleClick()
	 * ['Page', 'handleClick'] // Page::handleClick()
	 * 'handleClick' // global function handleClick()
	 * ~~~
	 *
	 * The event handler must be defined with the following signature,
	 *
	 * ~~~
	 * function ($event)
	 * ~~~
	 *
	 * where `$event` is an [[Event]] object which includes parameters associated with the event.
	 *
	 * @param string $name the event name
	 * @param callable $handler the event handler
	 * @param mixed $data the data to be passed to the event handler when the event is triggered.
	 * When the event handler is invoked, this data can be accessed via [[Event::data]].
	 * @param boolean $append whether to append new event handler to the end of the existing
	 * handler list. If false, the new handler will be inserted at the beginning of the existing
	 * handler list.
	 * @see off()
	 */
	public function on($name, $handler, $data = null, $append = true)
	{
		if ($append || !array_key_exists($name, $this->_events))
		{
			$this->_events[$name][] = [$handler, $data];
		}
		else
		{
			array_unshift($this->_events[$name], [$handler, $data]);
		}
	}

	/**
	 * Detaches an existing event handler from this component.
	 * This method is the opposite of [[on()]].
	 * @param string $name event name
	 * @param callable $handler the event handler to be removed.
	 * If it is null, all handlers attached to the named event will be removed.
	 * @return boolean if a handler is found and detached
	 * @see on()
	 */
	public function off($name, $handler = null)
	{
		if (!array_key_exists($name, $this->_events))
		{
			return false;
		}
		if ($handler === null)
		{
			unset($this->_events[$name]);
			return true;
		}
		else
		{
			$removed = false;
			foreach ($this->_events[$name] as $i => $event)
			{
				if ($event[0] === $handler)
				{
					unset($this->_events[$name][$i]);
					$removed = true;
				}
			}
			if ($removed)
			{
				$this->_events[$name] = array_values($this->_events[$name]);
			}
			return $removed;
		}
	}

	/**
	 * Triggers an event.
	 * This method represents the happening of an event. It invokes
	 * all attached handlers for the event including class-level handlers.
	 * @param string $name the event name
	 * @param Event $event the event parameter. If not set, a default [[Event]] object will be created.
	 */
	public function trigger($name, $model, Event $event = null)
	{
		if (!empty($this->_events[$name]))
		{
			if ($event === null)
			{
				$event = new Event;
			}
			if ($event->sender === null)
			{
				$event->sender = $model;
			}
			$event->handled = false;
			$event->name = $name;
			foreach ($this->_events[$name] as $handler)
			{
				$event->data = $handler[1];
				call_user_func($handler[0], $event);
				// stop further handling if the event is handled
				if ($event->handled)
				{
					return;
				}
			}
		}
		// invoke class-level attached handlers
		Event::trigger($model, $name, $event);
	}

	/**
	 * This method is called after the object is created by cloning an existing one.
	 * It removes all behaviors because they are attached to the old object.
	 */
	public function __clone()
	{
		$this->_events = [];
	}

}
