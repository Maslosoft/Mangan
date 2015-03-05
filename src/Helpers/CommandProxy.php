<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Command;
use Maslosoft\Mangan\Exceptions\CommandNotFoundException;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Storage\CommandProxyStorage;

/**
 * CommandProxy
 * This evalueates commands only if available
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class CommandProxy extends Command
{

	/**
	 * Static store of available commands
	 * @var CommandProxyStorage
	 */
	private $available = null;

	public function __construct(IAnnotated $model = null)
	{
		parent::__construct($model);
		$this->available = new CommandProxyStorage($this, Mangan::fromModel($model)->connectionId);
	}

	public function isAvailable($command)
	{
		if (!isset($this->available->$command))
		{
			return true;
		}
		return $this->available->$command;
	}

	public function call($command, $arguments = [])
	{
		if ($this->isAvailable($command))
		{
			try
			{
				parent::call($command, $arguments);
			}
			catch (CommandNotFoundException $e)
			{
				$this->available->$command = false;
			}
		}
	}

}
