<?php

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
		assert($finder instanceof ModelAwareInterface);
		Event::trigger($finder->getModel(), FinderInterface::EventAfterCount);
	}

	public function afterExists(FinderInterface $finder)
	{
		assert($finder instanceof ModelAwareInterface);
		Event::trigger($finder->getModel(), FinderInterface::EventAfterExists);
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
		assert($finder instanceof ModelAwareInterface);
		if (!Event::hasHandler($finder->getModel(), FinderInterface::EventBeforeCount))
		{
			return true;
		}
		return Event::handled($finder->getModel(), FinderInterface::EventBeforeCount);
	}

	/**
	 * Trigger before exists event
	 * @return boolean
	 */
	public function beforeExists(FinderInterface $finder)
	{
		assert($finder instanceof ModelAwareInterface);
		if (!Event::hasHandler($finder->getModel(), FinderInterface::EventBeforeExists))
		{
			return true;
		}
		return Event::handled($finder->getModel(), FinderInterface::EventBeforeExists);
	}

	/**
	 * Trigger before find event
	 * @return boolean
	 */
	public function beforeFind(FinderInterface $finder)
	{
		assert($finder instanceof ModelAwareInterface);
		if (!Event::hasHandler($finder->getModel(), FinderInterface::EventBeforeFind))
		{
			return true;
		}
		return Event::handled($finder->getModel(), FinderInterface::EventBeforeFind);
	}

}
