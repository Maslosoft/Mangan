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

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Interfaces\FinderEventsInterface;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Interfaces\ModelAwareInterface;

/**
 * FinderEvents
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class FinderEvents implements FinderEventsInterface
{

	public function afterCount(FinderInterface $finder)
	{
		$this->trigger($finder, FinderInterface::EventAfterCount);
	}

	public function afterExists(FinderInterface $finder)
	{
		$this->trigger($finder, FinderInterface::EventAfterExists);
	}

	public function afterFind(FinderInterface $finder, AnnotatedInterface $model)
	{
		Event::trigger($model, FinderInterface::EventAfterFind);
	}

	/**
	 * Trigger before count event
	 * @return boolean
	 */
	public function beforeCount(FinderInterface $finder)
	{
		return $this->handle($finder, FinderInterface::EventBeforeCount);
	}

	/**
	 * Trigger before exists event
	 * @return boolean
	 */
	public function beforeExists(FinderInterface $finder)
	{
		return $this->handle($finder, FinderInterface::EventBeforeExists);
	}

	/**
	 * Trigger before find event
	 * @return boolean
	 */
	public function beforeFind(FinderInterface $finder)
	{
		return $this->handle($finder, FinderInterface::EventBeforeFind);
	}

	protected function trigger($finder, $event)
	{
		assert($finder instanceof ModelAwareInterface);
		Event::trigger($finder->getModel(), $event);
	}

	protected function handle($finder, $event)
	{
		assert($finder instanceof ModelAwareInterface);
		if (!Event::hasHandler($finder->getModel(), $event))
		{
			return true;
		}
		return Event::handled($finder->getModel(), $event);
	}

}
