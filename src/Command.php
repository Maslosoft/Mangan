<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Mangan\Exceptions\CommandException;
use Maslosoft\Mangan\Exceptions\CommandNotFoundException;
use Maslosoft\Mangan\Helpers\CollectionNamer;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * Command
 * 
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Command
{
	use Traits\AvailableCommands;
	/**
	 *
	 * @var IAnnotated
	 */
	private $model = null;

	/**
	 *
	 * @var Mangan
	 */
	private $mn = null;

	/**
	 *
	 * @var ManganMeta
	 */
	private $meta = null;

	public function __construct(IAnnotated $model = null)
	{
		$this->model = $model;
		if (!$model)
		{
			$this->mn = Mangan::instance();
			return;
		}
		$this->mn = Mangan::fromModel($model);
		$this->meta = ManganMeta::create($model);
	}

	public function call($command, $arguments = [])
	{
		$arg = $this->model ? CollectionNamer::nameCollection($this->model) : true;
		$cmd = [$command => $arg];
		if (is_array($arguments) && count($arguments))
		{
			$cmd = array_merge($cmd, $arguments);
		}
		$result = $this->mn->getDbInstance()->command($cmd);

		if (array_key_exists('errmsg', $result) && array_key_exists('ok', $result) && $result['ok'] == 0)
		{
			if (array_key_exists('bad cmd', $result))
			{
				$badCmd = key($result['bad cmd']);
				if ($badCmd == $command)
				{
					throw new CommandNotFoundException(sprintf('Command `%s` not found', $command));
				}
			}
			throw new CommandException(sprintf('Could not execute command `%s`, mongo returned: "%s"', $command, $result['errmsg']));
		}
		return $result;
	}

	public function __call($name, $arguments)
	{
		if (count($arguments))
		{
			return $this->call($name, $arguments[0]);
		}
		return $this->call($name);
	}

}
