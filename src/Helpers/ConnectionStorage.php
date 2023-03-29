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

use Maslosoft\EmbeDi\StaticStorage;
use MongoDB\Client;
use MongoDB;

/**
 * ConnectionStorage
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConnectionStorage extends StaticStorage
{

	/**
	 * Mongo DB instance
	 * @var MongoDB
	 */
	public $mongoDB = null;

	/**
	 * Mongo Client instance
	 * @var Client
	 */
	public $mongoClient = null;

}
