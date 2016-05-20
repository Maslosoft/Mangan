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

namespace Maslosoft\Mangan\Events;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Mangan\Interfaces\Events\EventInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use UnexpectedValueException;

/**
 * This is based on Yii 2 Events
 */
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * Event is the base class for all event classes.
 *
 * It encapsulates the parameters associated with an event.
 * The [[sender]] property describes who raises the event.
 * And the [[handled]] property indicates if the event is handled.
 * If an event handler sets [[handled]] to be true, the rest of the
 * uninvoked handlers will no longer be called to handle the event.
 *
 * Additionally, when attaching an event handler, extra data may be passed
 * and be available via the [[data]] property when the event handler is invoked.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Event implements EventInterface
{

	/**
	 * @var string the event name. This property is set by [[Component::trigger()]] and [[trigger()]].
	 * Event handlers may use this property to check what event it is handling.
	 */
	public $name;

	/**
	 * @var object the sender of this event. If not set, this property will be
	 * set as the object whose "trigger()" method is called.
	 * This property may also be a `null` when this event is a
	 * class-level event which is triggered in a static context.
	 */
	public $sender;

	/**
	 * @var boolean whether the event is handled. Defaults to false.
	 * When a handler sets this to be true, the event processing will stop and
	 * ignore the rest of the uninvoked event handlers.
	 */
	public $handled = false;

	/**
	 * @var mixed the data that is passed to [[Component::on()]] when attaching an event handler.
	 * Note that this varies according to which event handler is currently executing.
	 */
	public $data;

	/**
	 * Array of events
	 * @var EventInterface[]
	 */
	private static $_events = [];

	/**
	 * Attaches an event handler to a class-level event.
	 *
	 * When a class-level event is triggered, event handlers attached
	 * to that class and all parent classes will be invoked.
	 *
	 * For example, the following code attaches an event handler to document's
	 * `afterInsert` event:
	 *
	 * ~~~
	 * Event::on($model, EntityManager::EventAfterInsert, function ($event) {
	 * 		var_dump(get_class($event->sender) . ' is inserted.');
	 * });
	 * ~~~
	 *
	 * The handler will be invoked for every successful document insertion.
	 *
	 * @param AnnotatedInterface|string $model the object specifying the class-level event.
	 * @param string $name the event name.
	 * @param callable $handler the event handler.
	 * @param mixed $data the data to be passed to the event handler when the event is triggered.
	 * When the event handler is invoked, this data can be accessed via [[Event::data]].
	 * @param boolean $append whether to append new event handler to the end of the existing
	 * handler list. If false, the new handler will be inserted at the beginning of the existing
	 * handler list.
	 * @see off()
	 */
	public static function on($model, $name, $handler, $data = null, $append = true)
	{
		$class = self::_getName($model);
		if ($append || empty(self::$_events[$name][$class]))
		{
			self::$_events[$name][$class][] = [$handler, $data];
		}
		else
		{
			array_unshift(self::$_events[$name][$class], [$handler, $data]);
		}
	}

	/**
	 * Detaches an event handler from a class-level event.
	 *
	 * This method is the opposite of [[on()]].
	 *
	 * @param AnnotatedInterface $model the object specifying the class-level event.
	 * @param string $name the event name.
	 * @param callable $handler the event handler to be removed.
	 * If it is null, all handlers attached to the named event will be removed.
	 * @return boolean whether a handler is found and detached.
	 * @see on()
	 */
	public static function off(AnnotatedInterface $model, $name, $handler = null)
	{
		$class = self::_getName($model);
		if (empty(self::$_events[$name][$class]))
		{
			return false;
		}
		if ($handler === null)
		{
			unset(self::$_events[$name][$class]);
			return true;
		}
		else
		{
			$removed = false;
			foreach (self::$_events[$name][$class] as $i => $event)
			{
				if ($event[0] === $handler)
				{
					unset(self::$_events[$name][$class][$i]);
					$removed = true;
				}
			}
			if ($removed)
			{
				self::$_events[$name][$class] = array_values(self::$_events[$name][$class]);
			}
			return $removed;
		}
	}

	/**
	 * Triggers a class-level event.
	 * This method will cause invocation of event handlers that are attached to the named event
	 * for the specified class and all its parent classes.
	 * @param AnnotatedInterface $model the object specifying the class-level event.
	 * @param string $name the event name.
	 * @param ModelEvent $event the event parameter. If not set, a default [[Event]] object will be created.
	 * @return bool True if event was triggered.
	 */
	public static function trigger(AnnotatedInterface $model, $name, &$event = null)
	{
		$wasTriggered = false;
		if (empty(self::$_events[$name]))
		{
			return self::_propagate($model, $name, $event);
		}
		if ($event === null)
		{
			$event = new ModelEvent();
		}
		$event->handled = false;
		$event->name = $name;

		if ($event->sender === null)
		{
			$event->sender = $model;
		}
		$className = self::_getName($model);

		// Iterate over parent classes and trigger events
		do
		{
			if (empty(self::$_events[$name][$className]))
			{
				continue;
			}

			foreach (self::$_events[$name][$className] as $handler)
			{
				$event->data = $handler[1];
				call_user_func($handler[0], $event);
				$wasTriggered = true;

				// Some event was not handled, return false
				if (!$event->handled)
				{
					return false;
				}
			}
		}
		while (($className = get_parent_class($className)) !== false);

		// Propagate events to sub objects
		return self::_propagate($model, $name, $event) || $wasTriggered;
	}

	/**
	 * Triggers a class-level event and checks if it's valid.
	 * If don't have event handler returns true. If event handler is set, return true if `Event::isValid`.
	 * This method will cause invocation of event handlers that are attached to the named event
	 * for the specified class and all its parent classes.
	 * @param AnnotatedInterface $model the object specifying the class-level event.
	 * @param string $name the event name.
	 * @param ModelEvent $event the event parameter. If not set, a default [[ModelEvent]] object will be created.
	 * @return bool True if event was triggered and is valid.
	 */
	public static function valid(AnnotatedInterface $model, $name, $event = null)
	{
		if (Event::trigger($model, $name, $event))
		{
			return $event->isValid;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Triggers a class-level event and checks if it's handled.
	 * If don't have event handler returns true. If event handler is set, return true if `Event::handled`.
	 * This method will cause invocation of event handlers that are attached to the named event
	 * for the specified class and all its parent classes.
	 * @param AnnotatedInterface $model the object specifying the class-level event.
	 * @param string $name the event name.
	 * @param ModelEvent $event the event parameter. If not set, a default [[Event]] object will be created.
	 * @return bool|null True if handled, false otherway, null if not triggered
	 */
	public static function handled(AnnotatedInterface $model, $name, $event = null)
	{
		if (Event::trigger($model, $name, $event))
		{
			return $event->handled;
		}
		return true;
	}

	/**
	 * Check if model has event handler.
	 * **IMPORTANT**: It does not check for propagated events
	 * @param AnnotatedInterface $model the object specifying the class-level event
	 * @param string $name the event name.
	 * @return bool True if has handler
	 */
	public static function hasHandler(AnnotatedInterface $model, $name)
	{
		$className = self::_getName($model);

		do
		{
			if (!empty(self::$_events[$name][$className]))
			{
				return true;
			}
		}
		while (($className = get_parent_class($className)) !== false);
		return false;
	}

	/**
	 * Get class name
	 * @param AnnotatedInterface $class
	 * @return string
	 */
	private static function _getName($class)
	{
		if (is_object($class))
		{
			$class = get_class($class);
		}
		else
		{
			if (!ClassChecker::exists($class))
			{
				throw new UnexpectedValueException(sprintf("Class `%s` not found", $class));
			}
		}
		return ltrim($class, '\\');
	}

	/**
	 * Propagate event
	 * @param AnnotatedInterface $class
	 * @param string $name
	 * @param ModelEvent|null $event
	 */
	private static function _propagate(AnnotatedInterface $class, $name, &$event = null)
	{
		$wasTriggered = false;
		if ($event && !$event->propagate())
		{
			return false;
		}
		$meta = ManganMeta::create($class);
		foreach ($meta->properties('propagateEvents') as $property => $propagate)
		{
			if (!$propagate)
			{
				continue;
			}
			if (!$class->$property)
			{
				continue;
			}
			// Trigger for arrays
			if (is_array($class->$property))
			{
				foreach ($class->$property as $object)
				{
					$wasTriggered = self::trigger($object, $name, $event);
				}
				continue;
			}
			// Trigger for single value
			$wasTriggered = self::trigger($class->$property, $name, $event);
		}
		return $wasTriggered;
	}

}
