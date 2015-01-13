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

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\EmbeDi\StaticStorage;
use MongoClient;
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
	 * @var MongoClient
	 */
	public $mongoClient = null;

}
