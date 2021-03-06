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

namespace Maslosoft\Mangan\Model\Command;

/**
 * Roles helper model
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Roles extends DbCommandModel
{

	public $read = false;
	public $readWrite = false;
	public $dbAdmin = false;
	public $dbOwner = false;
	public $userAdmin = false;
	public $dbName = '';

	/**
	 *
	 * @param string $dbName Database for which roles will be applied
	 * @param array $roles
	 */
	public function __construct($dbName = '', $roles = [])
	{
		$this->dbName = $dbName;
		foreach ($roles as $name)
		{
			$this->$name = true;
		}
	}

	public function toArray($except = [])
	{
		$parent = parent::toArray(['dbName']);
		$result = [];
		foreach ($parent as $name => $value)
		{
			if (!$value)
			{
				continue;
			}
			if (!empty($this->dbName))
			{
				$result[] = [
					'role' => $name,
					'db' => $this->dbName
				];
			}
			else
			{
				$result[] = $name;
			}
		}
		return $result;
	}

}
