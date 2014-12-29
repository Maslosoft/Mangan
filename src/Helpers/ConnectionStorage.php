<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
