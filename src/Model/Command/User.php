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
 * User
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class User extends DbCommandModel
{

	public $user = '';
	public $pwd = '';
	public $customData = null;

	/**
	 * Roles for user. [See built-in MongoDB roles](https://docs.mongodb.com/manual/reference/built-in-roles/#built-in-roles)
	 * for details.
	 * @var array|Roles
	 */
	public $roles = [];

}
