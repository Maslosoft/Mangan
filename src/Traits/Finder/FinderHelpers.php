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

namespace Maslosoft\Mangan\Traits\Finder;

use Maslosoft\Mangan\Interfaces\Adapters\FinderAdapterInterface;
use Maslosoft\Mangan\Interfaces\FinderEventsInterface;
use Maslosoft\Mangan\Interfaces\ProfilerInterface;
use Maslosoft\Mangan\Interfaces\ScopeManagerInterface;
use Maslosoft\Mangan\Profillers\NullProfiler;

/**
 * FinderHelpers
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait FinderHelpers
{

	/**
	 *
	 * @var FinderAdapterInterface
	 */
	private $adapter = null;

	/**
	 * Scope manager instance
	 * @var ScopeManagerInterface
	 */
	private $scopeManager = null;

	/**
	 * Finder events instance.
	 *
	 * This must hold events helper
	 *
	 * @var FinderEventsInterface
	 */
	private $finderEvents = null;

	/**
	 * Profiler instance
	 * @var ProfilerInterface
	 */
	private $profiler = null;

	public function getAdapter()
	{
		return $this->adapter;
	}

	public function getScopeManager()
	{
		return $this->scopeManager;
	}

	public function getFinderEvents()
	{
		return $this->finderEvents;
	}

	public function getProfiler()
	{
		if (empty($this->profiler))
		{
			$this->profiler = new NullProfiler();
		}
		return $this->profiler;
	}

	public function setAdapter(FinderAdapterInterface $adapter)
	{
		$this->adapter = $adapter;
		return $this;
	}

	public function setScopeManager(ScopeManagerInterface $scopeManager)
	{
		$this->scopeManager = $scopeManager;
		return $this;
	}

	public function setFinderEvents(FinderEventsInterface $finderEvents)
	{
		$this->finderEvents = $finderEvents;
		return $this;
	}

	public function setProfiler(ProfilerInterface $profiler)
	{
		$this->profiler = $profiler;
		return $this;
	}

}
