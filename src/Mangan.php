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

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\EmbeDi\EmbeDi;
use Maslosoft\Mangan\Decorators\DbRefArrayDecorator;
use Maslosoft\Mangan\Decorators\DbRefDecorator;
use Maslosoft\Mangan\Decorators\EmbeddedArrayDecorator;
use Maslosoft\Mangan\Decorators\EmbeddedDecorator;
use Maslosoft\Mangan\Decorators\I18NDecorator;
use Maslosoft\Mangan\Decorators\Model\AliasDecorator;
use Maslosoft\Mangan\Decorators\Model\ClassNameDecorator;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Helpers\ConnectionStorage;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Transformers\CriteriaArray;
use Maslosoft\Mangan\Transformers\DocumentArray;
use Maslosoft\Mangan\Transformers\Filters\DocumentArrayFilter;
use Maslosoft\Mangan\Transformers\Filters\JsonFilter;
use Maslosoft\Mangan\Transformers\Filters\PersistentFilter;
use Maslosoft\Mangan\Transformers\ITransformator;
use Maslosoft\Mangan\Transformers\JsonArray;
use Maslosoft\Mangan\Transformers\RawArray;
use Maslosoft\Mangan\Transformers\SafeArray;
use Maslosoft\Mangan\Validators\BuiltIn\UniqueValidator;
use Maslosoft\Mangan\Validators\Proxy\BooleanProxy;
use Maslosoft\Mangan\Validators\Proxy\BooleanValidator;
use Maslosoft\Mangan\Validators\Proxy\UniqueProxy;
use MongoClient;
use MongoDB;
use MongoException;

/**
 * MongoDB
 *
 * This is merge work of tyohan, Alexander Makarov and mine
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @since v1.0
 */
class Mangan
{

	const DefaultConnectionId = 'mongodb';

	use Traits\Defaults\MongoClientOptions;

	/**
	 * @var string host:port
	 *
	 * Correct syntax is:
	 * mongodb://[username:password@]host1[:port1][,host2[:port2:],...]
	 * @example mongodb://localhost:27017
	 * @since v1.0
	 */
	public $connectionString = 'mongodb://localhost:27017';

	/**
	 * Configuration of decorators for transformers
	 * Array key is decorator class name or interface, values are decorator class names.
	 * @var string[][]
	 */
	public $decorators = [
		ITransformator::class => [
			DbRefArrayDecorator::class,
			DbRefDecorator::class,
			EmbeddedArrayDecorator::class,
			EmbeddedDecorator::class,
			AliasDecorator::class,
		],
		CriteriaArray::class => [
			I18NDecorator::class,
		],
		DocumentArray::class => [
			ClassNameDecorator::class,
		],
		JsonArray::class => [
			ClassNameDecorator::class,
		],
		RawArray::class => [
			I18NDecorator::class,
			ClassNameDecorator::class,
		]
	];

	/**
	 * Configuration of property filters for transformers
	 * Array key is decorator class name or interface, values are filter class names.
	 * @var string[][]
	 */
	public $filters = [
		ITransformator::class => [
		],
		DocumentArray::class => [
			DocumentArrayFilter::class,
		],
		JsonArray::class => [
			JsonFilter::class,
		],
		RawArray::class => [
			PersistentFilter::class
		],
		SafeArray::class => [
		],
	];

	/**
	 * Mapping for validators. Key is validator proxy class name, value is concrete validator implementation
	 * @var string[]
	 */
	public $validators = [
		BooleanProxy::class => BooleanValidator::class,
		UniqueProxy::class => UniqueValidator::class,
	];

	/**
	 * Connection ID
	 * @var string
	 */
	public $connectionId = 'mongodb';

	/**
	 * @var string $dbName name of the Mongo database to use
	 * @since v1.0
	 */
	public $dbName = null;

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
	 * TODO Move to finder
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
	 * Queries profiling.
	 * Defaults to false. This should be mainly enabled and used during development
	 * to find out the bottleneck of mongo queries.
	 * @var boolean whether to enable profiling the mongo queries being executed.
	 */
	public $enableProfiling = false;

	/**
	 * Connection storage
	 * @var ConnectionStorage
	 */
	private $_cs = null;

	/**
	 * Embedi instance
	 * @var EmbeDi
	 */
	private $_di = null;

	public function __construct($connectionId = self::DefaultConnectionId)
	{
		$this->_di = new EmbeDi($connectionId);
		if (!$connectionId)
		{
			$connectionId = self::DefaultConnectionId;
		}
		$this->connectionId = $connectionId;
		$this->_di->configure($this);
		$this->_cs = new ConnectionStorage($this, $connectionId);
	}

	/**
	 * Get instance of Mangan component
	 * @new
	 * @param string $connectionId
	 * @return Mangan
	 */
	public static function instance($connectionId = self::DefaultConnectionId)
	{
		return new self($connectionId);
	}

	/**
	 * Get instance of Mangan configured for particular model
	 * @param IAnnotated $model
	 */
	public static function fromModel(IAnnotated $model)
	{
		$connectionId = ManganMeta::create($model)->type()->connectionId;
		return new self($connectionId);
	}

	public function init()
	{
		$this->_di->store($this);
	}

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
	 * @throws ManganException
	 * @since v1.0
	 */
	public function getConnection()
	{
		if ($this->_cs->mongoClient === null)
		{
			if (!$this->connectionString)
			{
				throw new ManganException('Mangan.connectionString cannot be empty.');
			}

			$options = [];
			foreach ($this->_getOptionNames() as $name)
			{
				if (isset($this->$name))
				{
					$options[$name] = $this->$name;
				}
			}
			$this->_cs->mongoClient = new MongoClient($this->connectionString, $options);

			return $this->_cs->mongoClient;
		}
		else
		{
			return $this->_cs->mongoClient;
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
		$this->_cs->mongoClient = $connection;
	}

	/**
	 * Get MongoDB instance
	 * @return MongoDB Mongo DB instance
	 * @since v1.0
	 */
	public function getDbInstance()
	{
		if ($this->_cs->mongoDB === null)
		{
			if (!$this->dbName)
			{
				throw new ManganException(sprintf("Database name is required for connectionId: `%s`", $this->connectionId));
			}
			try
			{
				$db = $this->getConnection()->selectDB($this->dbName);
			}
			catch (MongoException $e)
			{
				throw new ManganException(sprintf('Invalid database name: `%s`, for connectionId: `%s`', $this->dbName, $this->connectionId));
			}
			return $this->_cs->mongoDB = $db;
		}
		else
		{
			return $this->_cs->mongoDB;
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
		$this->_cs->mongoDB = $this->getConnection()->selectDb($name);
	}

	/**
	 * Closes the currently active Mongo connection.
	 * It does nothing if the connection is already closed.
	 * @since v1.0
	 */
	protected function close()
	{
		if ($this->_cs->mongoClient !== null)
		{
			$this->_cs->mongoClient->close();
			$this->_cs->mongoClient = null;
			$this->_cs->mongoDB = null;
		}
	}

	/**
	 * Drop the current DB
	 * TODO Move to entity manager
	 * @since v1.0
	 */
	public function dropDb()
	{
		$this->getDbInstance()->drop();
	}

}
