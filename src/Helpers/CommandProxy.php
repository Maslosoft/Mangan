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

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
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

	public function __construct(AnnotatedInterface $model = null)
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
				return parent::call($command, $arguments);
			}
			catch (CommandNotFoundException $e)
			{
				$this->available->$command = false;
			}
		}
	}

}
