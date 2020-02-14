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

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Exceptions\CommandException;
use Maslosoft\Mangan\Exceptions\CommandNotFoundException;
use Maslosoft\Mangan\Helpers\CollectionNamer;
use Maslosoft\Mangan\Model\Command\User;
use Maslosoft\Mangan\Traits\AvailableCommands;

/**
 * Command
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Command
{

	use AvailableCommands;

	/**
	 * @var AnnotatedInterface
	 */
	private $model;

	/**
	 * @var Mangan
	 */
	private $mn;

	/**
	 * @var string 
	 */
	private $collection;

	public function __construct(AnnotatedInterface $model = null, Mangan $mangan = null)
	{
		$this->model = $model;
		if ($mangan !== null)
		{
			$this->mn = $mangan;
		}
		if ($model === null)
		{
			$this->mn = Mangan::fly();
			return;
		}
		$this->mn = Mangan::fromModel($model);
		$this->collection = CollectionNamer::nameCollection($model);
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
			elseif (strpos($result['errmsg'], 'no such command') !== false)
			{
				throw new CommandNotFoundException(sprintf('Command `%s` not found', $command));
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

	/**
	 * Explicitly creates a collection or view.
	 *
	 * Parameter `$params` depends on MongoDB version,
	 * see (official documentation)[https://docs.mongodb.com/manual/reference/command/create/] for details
	 *
	 * @param string $collectionName The name of the new collection
	 * @param array $params
	 * @return array
	 */
	public function create($collectionName, $params = [])
	{
		$cmd = [
			'create' => $collectionName
		];
		return $this->mn->getDbInstance()->command(array_merge($cmd, $params));
	}

	public function createUser(User $user, $writeConcerns = [])
	{
		$cmd = [
			'createUser' => $user->user,
			'pwd' => $user->pwd,
		];
		if (!empty($user->customData))
		{
			assert(is_object($user->customData));
			$cmd['customData'] = $user->customData;
		}
		$cmd = array_merge($cmd, $user->toArray(['user', 'customData']));
		return $this->mn->getDbInstance()->command($cmd);
	}

	public function dropUser($username, $writeConcerns = [])
	{
		if ($username instanceof User)
		{
			$username = $username->user;
		}
		$cmd = [
			'dropUser' => $username
		];
		return $this->mn->getDbInstance()->command(array_merge($cmd, $writeConcerns));
	}

	public function createIndex($keys, $options = [])
	{
		// Ensure array
		if(empty($options))
		{
			$options = [];
		}
		return $this->mn->getDbInstance()->selectCollection($this->collection)->createIndex($keys, $options);
	}

	/**
	 * NOTE: This is broken
	 * @return array
	 * @throws Exceptions\ManganException
	 */
	public function getIndexes()
	{
		return $this->mn->getDbInstance()->selectCollection($this->collection)->getIndexInfo();
	}

	/**
	 * The `collStats` command returns a variety of storage statistics for a given collection.
	 *
	 * @param string $collectionName The name of the target collection. If the collection does not exist, collStats returns an error message.
	 * @param int $scale Optional. The scale used in the output to display the sizes of items. By default, output displays sizes in bytes. To display kilobytes rather than bytes, specify a scale value of 1024. The scale factor rounds values to whole numbers.
	 * @param boolean $verbose Optional. When true, collStats increases reporting for the MMAPv1 Storage Engine. Defaults to false.
	 * @return array
	 */
	public function collStats($collectionName, $scale = 1, $verbose = false)
	{
		$cmd = [
			'collStats' => $collectionName,
			'scale' => $scale,
			'verbose' => $verbose
		];
		return $this->mn->getDbInstance()->command($cmd);
	}

}
