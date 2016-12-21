<?php

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Interfaces\FinderEventsInterface;
use Maslosoft\Mangan\Interfaces\FinderInterface;

/**
 * FinderEvents
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class FinderEvents implements FinderEventsInterface
{

	public function afterCount($model)
	{
		Event::trigger($model, FinderInterface::EventAfterCount);
	}

	public function afterExists($model)
	{
		Event::trigger($model, FinderInterface::EventAfterExists);
	}

	public function afterFind($model)
	{
		Event::trigger($model, FinderInterface::EventAfterFind);
	}

	/**
	 * Trigger before count event
	 * @return boolean
	 */
	public function beforeCount($model)
	{
		if (!Event::hasHandler($model, FinderInterface::EventBeforeCount))
		{
			return true;
		}
		return Event::handled($model, FinderInterface::EventBeforeCount);
	}

	/**
	 * Trigger before exists event
	 * @return boolean
	 */
	public function beforeExists($model)
	{
		if (!Event::hasHandler($model, FinderInterface::EventBeforeExists))
		{
			return true;
		}
		return Event::handled($model, FinderInterface::EventBeforeExists);
	}

	/**
	 * Trigger before find event
	 * @return boolean
	 */
	public function beforeFind($model)
	{
		if (!Event::hasHandler($model, FinderInterface::EventBeforeFind))
		{
			return true;
		}
		return Event::handled($model, FinderInterface::EventBeforeFind);
	}

}
