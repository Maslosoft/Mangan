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
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Interfaces\FinderEventsInterface;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\Mangan\Interfaces\ModelAwareInterface;

/**
 * EmptyFinderEvents prevents triggering any events at all.
 * This is for special cases.
 *
 * @internal
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmptyFinderEvents implements FinderEventsInterface
{

	public function afterCount(FinderInterface $finder)
	{
	}

	public function afterExists(FinderInterface $finder)
	{
	}

	public function afterFind(FinderInterface $finder, AnnotatedInterface $model)
	{
	}

	/**
	 * Trigger before count event
	 * @return boolean
	 */
	public function beforeCount(FinderInterface $finder)
	{
		return true;
	}

	/**
	 * Trigger before exists event
	 * @return boolean
	 */
	public function beforeExists(FinderInterface $finder)
	{
		return true;
	}

	/**
	 * Trigger before find event
	 * @return boolean
	 */
	public function beforeFind(FinderInterface $finder)
	{
		return true;
	}
}
