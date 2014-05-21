<?php

/**
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @license New BSD license
 * @version 1.3
 * @category ext
 * @package ext.YiiMongoDbSuite
 */

namespace Maslosoft\Mangan;

use Maslosoft\Mangan\Core\ApplicationComponent;
use MongoClient;
use MongoConnectionException;
use Yii;

/**
 * TODO Check if needed and remove these
 */
Yii::setPathOfAlias('yii-mangan', __DIR__);
Yii::import('yii-mangan.models.*');

/**
 * MongoDB
 *
 * This is merge work of tyohan, Alexander Makarov and mine
 * @since v1.0
 */
class MongoDB extends ApplicationComponent
{

	/**
	 * @var string host:port
	 *
	 * Correct syntax is:
	 * mongodb://[username:password@]host1[:port1][,host2[:port2:],...]
	 * @example mongodb://localhost:27017
	 * @since v1.0
	 */
	public $connectionString;

	/**
	 * @var string replicaSet The name of the replica set to connect to. If this is given, the master will
	 * be determined by using the ismaster database command on the seeds, so the driver may end up connecting
	 * to a server that was not even listed.
	 * @example myReplicaSet
	 * @since v1.3.7
	 */
	public $replicaSet = null;

	/**
	 * @var int timeout For how long the driver should try to connect to the database (in milliseconds).
	 * @example 2000
	 * @since v1.3.7
	 */
	public $timeout = 2000;

	/**
	 * @var boolean $autoConnect whether the Mongo connection should be automatically established when
	 * the component is being initialized. Defaults to true. Note, this property is only
	 * effective when the MongoDB object is used as an application component.
	 * @since v1.0
	 */
	public $autoConnect = true;

	/**
	 * @var false|string $persistentConnection false for non-persistent connection, string for persistent connection id to use
	 * @since v1.0
	 */
	public $persistentConnection = false;

	/**
	 * @var string $dbName name of the Mongo database to use
	 * @since v1.0
	 */
	public $dbName = null;

	/**
	 * @var MongoDB $_mongoDb instance of MongoDB driver
	 */
	private $_mongoDb;

	/**
	 * @var MongoClient $_mongoConnection instance of MongoDB driver
	 */
	private $_mongoConnection;

	/**
	 * If set to TRUE all internal DB operations will use FSYNC flag with data modification requests,
	 * in other words, all write operations will have to wait for a disc sync!
	 *
	 * MongoDB default value for this flag is: FALSE.
	 *
	 * @var boolean $fsyncFlag state of FSYNC flag to use with internal connections (global scope)
	 * @since v1.0
	 */
	public $fsyncFlag = false;

	/**
	 * If set to TRUE all internal DB operations will use SAFE flag with data modification requests.
	 *
	 * When SAFE flag is set to TRUE driver will wait for the response from DB, and throw an exception
	 * if something went wrong, is set to false, driver will only send operation to DB but will not wait
	 * for response from DB.
	 *
	 * MongoDB default value for this flag is: FALSE.
	 *
	 * @var boolean $safeFlag state of SAFE flag (global scope)
	 */
	public $safeFlag = false;

	/**
	 * If set to TRUE findAll* methods of models, will return {@see Cursor} instead of
	 * raw array of models.
	 *
	 * Generally you should want to have this set to TRUE as cursor use lazy-loading/instantiating of
	 * models, this is set to FALSE, by default to keep backwards compatibility.
	 *
	 * Note: {@see Cursor} does not implement ArrayAccess interface and cannot be used like an array,
	 * because offset access to cursor is highly ineffective and pointless.
	 *
	 * @var boolean $useCursor state of Use Cursor flag (global scope)
	 */
	public $useCursor = false;

	/**
	 * Storage location for temporary files used by the GridFS Feature.
	 * If set to null, component will not use temporary storage
	 * @var string $gridFStemporaryFolder
	 */
	public $gridFStemporaryFolder = null;

	/**
	 * Connect to DB if connection is already connected this method does nothing
	 * @since v1.0
	 */
	public function connect()
	{
		if (!$this->getConnection()->connected)
		{
			return $this->getConnection()->connect();
		}
	}

	/**
	 * Returns Mongo connection instance if not exists will create new
	 *
	 * @return MongoClient
	 * @throws MongoException
	 * @since v1.0
	 */
	public function getConnection()
	{
		if ($this->_mongoConnection === null)
		{
			try
			{
				Yii::trace('Opening MongoDB connection', 'Maslosoft.Mangan.MongoDB');
				if (empty($this->connectionString))
				{
					throw new MongoException(Yii::t('yii', 'MongoDB.connectionString cannot be empty.'));
				}

				$options = [ 'connect' => $this->autoConnect];

				if ($this->persistentConnection !== false)
				{
					$options['persist'] = $this->persistentConnection;
				}
				if (!is_null($this->replicaSet))
				{
					$options['replicaSet'] = $this->replicaSet;
				}
				if (!is_null($this->timeout))
				{
					if (version_compare(MongoClient::VERSION, '1.3.4', '>=') === true)
					{
						$options['connectTimeoutMS'] = $this->timeout;
					}
					else
					{
						$options['timeout'] = $this->timeout;
					}
				}


				$this->_mongoConnection = new MongoClient($this->connectionString, $options);

				return $this->_mongoConnection;
			}
			catch (MongoConnectionException $e)
			{
				throw new MongoException(Yii::t(
						'yii', 'MongoDB failed to open connection: {error}', ['{error}' => $e->getMessage()]
				), $e->getCode());
			}
		}
		else
		{
			return $this->_mongoConnection;
		}
	}

	/**
	 * Set the connection
	 *
	 * @param MongoClient $connection
	 * @since v1.0
	 */
	public function setConnection(MongoClient $connection)
	{
		$this->_mongoConnection = $connection;
	}

	/**
	 * Get MongoDB instance
	 * @since v1.0
	 */
	public function getDbInstance()
	{
		if ($this->_mongoDb === null)
		{
			return $this->_mongoDb = $this->getConnection()->selectDB($this->dbName);
		}
		else
		{
			return $this->_mongoDb;
		}
	}

	/**
	 * Set MongoDB instance
	 * Enter description here ...
	 * @param string $name
	 * @since v1.0
	 */
	public function setDbInstance($name)
	{
		$this->_mongoDb = $this->getConnection()->selectDb($name);
	}

	/**
	 * Closes the currently active Mongo connection.
	 * It does nothing if the connection is already closed.
	 * @since v1.0
	 */
	protected function close()
	{
		if ($this->_mongoConnection !== null)
		{
			$this->_mongoConnection->close();
			$this->_mongoConnection = null;
			Yii::trace('Closing MongoDB connection', 'Maslosoft.Mangan.MongoDB');
		}
	}

	/**
	 * If we have don't use persist connection, close it
	 * @since v1.0
	 */
	public function __destruct()
	{
		if (!$this->persistentConnection)
		{
			$this->close();
		}
	}

	/**
	 * Drop the current DB
	 * @since v1.0
	 */
	public function dropDb()
	{
		$this->_mongoDb->drop();
	}

}
