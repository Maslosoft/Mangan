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

use Exception;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\EmbeDi\EmbeDi;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Helpers\ConnectionStorage;
use Maslosoft\Mangan\Helpers\NotFoundResolver;
use Maslosoft\Mangan\Interfaces\EventHandlersInterface;
use Maslosoft\Mangan\Interfaces\Exception\ExceptionCodeInterface;
use Maslosoft\Mangan\Interfaces\ManganAwareInterface;
use Maslosoft\Mangan\Interfaces\ProfilerInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Profillers\NullProfiler;
use Maslosoft\Mangan\Signals\ConfigInit;
use Maslosoft\Mangan\Traits\Defaults\MongoClientOptions;
use Maslosoft\Signals\Signal;
use MongoDB;
use MongoDB\Client;
use MongoDB\Database;
use MongoDB\Driver\Manager;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * MongoDB
 *
 * This is merge work of tyohan, Alexander Makarov and mine
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @property LoggerInterface $logger Logger
 * @property-read string $version Current version
 * @since v1.0
 */
class Mangan implements LoggerAwareInterface
{

	public const DefaultConnectionId = 'mongodb';

	use MongoClientOptions;

	/**
	 * Correct syntax is:
	 * mongodb://[username:password@]host1[:port1][,host2[:port2:],...]
	 * @example mongodb://localhost:27017
	 * @var string host:port
	 * @since v1.0
	 */
	public $connectionString = 'mongodb://localhost:27017';

	/**
	 * Default annotations values configuration. This should contain
	 * array with keys same as annotation class name, and key-value
	 * pairs corresponding to annotation properties.
	 *
	 * Example:
	 * ```
	 * $annotationsDefaults = [
	 * 		I18NAnnotation::class => [
	 * 			'allowAny' => true,
	 * 			'allowDefault' => true
	 * 		]
	 * ];
	 * ```
	 *
	 */
	public $annotationsDefaults = [];

	/**
	 * Configuration of decorators for transformers
	 * Array key is decorator class name or interface, values are decorator class names.
	 * @var string[][]
	 */
	public $decorators = [];

	/**
	 * Configuration for finalizers.
	 * @see https://github.com/Maslosoft/Mangan/issues/36
	 * @var string[][]
	 */
	public $finalizers = [];

	/**
	 * Configuration of property filters for transformers
	 * Array key is decorator class name or interface, values are filter class names.
	 * @var string[][]
	 */
	public $filters = [];

	/**
	 * Mapping for validators. Key is validator proxy class name, value is concrete validator implementation
	 * @var string[]
	 */
	public $validators = [];

	/**
	 * Sanitizers remapping for common scenarios.
	 * @var string[][]
	 */
	public $sanitizersMap = [];

	/**
	 * Event handlers to attach on initialization.
	 *
	 * This should be list of class names implementing `EventHandlersInterface`
	 * or optionally list of arrays with configuration for `EventHandlersInterface`
	 * derived classes.
	 *
	 * @see EventHandlersInterface
	 * @var mixed[]
	 */
	public $eventHandlers = [];

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
	 * If set to TRUE findAll* methods of models, will return {@see Cursor} instead of
	 * raw array of models.
	 *
	 * Generally you should want to have this set to TRUE as cursor use lazy-loading/instantiating of
	 * models, this is set to FALSE, by default to keep backwards compatibility.
	 *
	 * Note: {@see Cursor} does not implement ArrayAccess interface and cannot be used like an array,
	 * because offset access to cursor is highly ineffective and pointless.
	 * @deprecated This has no effect, as cursor is always used
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
	private $cs = null;

	/**
	 * Embedi instance
	 * @var EmbeDi
	 */
	private $di = null;

	/**
	 * Logger
	 * @var LoggerInterface
	 */
	private $_logger = null;

	/**
	 * Profiller
	 * @var ProfilerInterface
	 */
	private $_profiler = null;

	/**
	 * Version number holder
	 * @var string
	 */
	private static $_version = null;

	/**
	 * Instances of mangan
	 * @var Mangan[]
	 */
	private static $mn = [];

	/**
	 * Hash map of class name to id. This is to reduce overhead of Mangan::fromModel()
	 * @var string[]
	 */
	private static $classToId = [];

	/**
	 * Create new mangan instance.
	 *
	 * **NOTE: While it's ok to use constructor to create Mangan, it is recommended to use
	 * Mangan::fly() to create/get instance, as creating new instance has some overhead.**
	 *
	 * @param string $connectionId
	 */
	public function __construct($connectionId = self::DefaultConnectionId)
	{
		static $initializedBc = false;
		if (!$initializedBc)
		{
			new NotFoundResolver(AnnotatedInterface::class, [
				'MongoId' => MongoDB\BSON\ObjectId::class,
				'MongoDate' => MongoDB\BSON\UTCDateTime::class,
			]);
		}


		$this->di = EmbeDi::fly($connectionId);

		// Load built-in config
		$config = ConfigManager::getDefault();

		// Gather additional config options via signals
		(new Signal)->emit(new ConfigInit($config, $connectionId));

		// Apply built-in configuration, as other configurations might not exists
		$this->di->apply($config, $this);

		if (empty($connectionId))
		{
			$connectionId = self::DefaultConnectionId;
		}
		$this->connectionId = $connectionId;

		// Apply any configurations loaded
		$this->di->configure($this);
		$this->cs = new ConnectionStorage($this, $connectionId);
		if (empty(self::$mn[$connectionId]))
		{
			self::$mn[$connectionId] = $this;
		}

		// Initialize event handlers once
		// TODO Possibly is should depend on instance ID?
		static $once = true;
		if ($once)
		{
			foreach ($this->eventHandlers as $config)
			{
				$eh = $this->di->apply($config);
				assert($eh instanceof EventHandlersInterface);
				$eh->setupHandlers();
			}
			$once = false;
		}
	}

	public function __get($name)
	{
		return $this->{'get' . ucfirst($name)}();
	}

	public function __set($name, $value)
	{
		$this->{'set' . ucfirst($name)}($value);
	}

	public function __isset(string $name): bool
	{
		return method_exists($this, 'get' . ucfirst($name));
	}

	/**
	 * Get mangan version
	 * @return string
	 */
	public function getVersion(): string
	{
		if (null === self::$_version)
		{
			self::$_version = require __DIR__ . '/version.php';
		}
		return self::$_version;
	}

	/**
	 * Set PSR compliant logger
	 * @param LoggerInterface $logger
	 */
	public function setLogger(LoggerInterface $logger): void
	{
		$this->_logger = $logger;
	}

	/**
	 * Get PSR compliant logger
	 * @return LoggerInterface
	 */
	public function getLogger(): LoggerInterface
	{
		if (null === $this->_logger)
		{
			$this->_logger = new NullLogger;
		}
		return $this->_logger;
	}

	/**
	 * Get profiler instance. This is guaranteed, if not configured will return NullProfiler.
	 * @see NullProfiler
	 * @return ProfilerInterface
	 */
	public function getProfiler(): ProfilerInterface
	{
		if (null === $this->_profiler)
		{
			$this->_profiler = new NullProfiler;
		}
		if ($this->_profiler instanceof ManganAwareInterface)
		{
			$this->_profiler->setMangan($this);
		}
		return $this->_profiler;
	}

	/**
	 * Set profiler instance
	 * @param ProfilerInterface $profiler
	 */
	public function setProfiler(ProfilerInterface $profiler): void
	{
		$this->_profiler = $profiler;
	}

	/**
	 * Get dependency injector.
	 * @return EmbeDi
	 */
	public function getDi(): EmbeDi
	{
		return $this->di;
	}

	/**
	 * Get flyweight instance of Mangan component.
	 * Only one instance will be created for each `$connectionId`.
	 *
	 * @new
	 * @param string $connectionId
	 * @return Mangan
	 */
	public static function fly($connectionId = self::DefaultConnectionId)
	{
		if (empty($connectionId))
		{
			$connectionId = self::DefaultConnectionId;
		}
		if (empty(self::$mn[$connectionId]))
		{
			self::$mn[$connectionId] = new static($connectionId);
		}
		return self::$mn[$connectionId];
	}

	/**
	 * Get instance of Mangan configured for particular model
	 * @param AnnotatedInterface|string $model
	 * @return static
	 */
	public static function fromModel($model)
	{
		if(is_object($model))
		{
			$key = get_class($model);
		}
		else
		{
			$key = $model;
		}
		if (isset(self::$classToId[$key]))
		{
			$connectionId = self::$classToId[$key];
		}
		else
		{
			$connectionId = ManganMeta::create($model)->type()->connectionId;
			self::$classToId[$key] = $connectionId;
		}
		return self::fly($connectionId);
	}

	public function init()
	{
		$this->di->store($this);
	}

	/**
	 * Connect to DB if connection is already connected this method return connection status.
	 *
	 * @deprecated
	 * @return bool Returns true if connected
	 * @throws ManganException
	 */
	public function connect()
	{
		if (!$this->getConnection()->connected)
		{
			return $this->getConnection()->connect();
		}
		return $this->getConnection()->connected;
	}

	/**
	 * Returns Mongo connection instance if not exists will create new
	 *
	 * @return Client
	 * @throws ManganException
	 * @since v1.0
	 */
	public function getConnection(): Client
	{
		if ($this->cs->mongoClient === null)
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
			$driverOptions = [
				'typeMap' => [
					'array' => 'array',
					'document' => 'array',
					'root' => 'array',
				],
			];
			$this->cs->mongoClient = new Client($this->connectionString, [], $driverOptions);
		}
		return $this->cs->mongoClient;
	}

	public function	getManager(): Manager
	{
		return $this->getConnection()->getManager();
	}

	/**
	 * Set the connection by supplying `Client` instance.
	 *
	 * Use this to set connection from external source.
	 * In most scenarios this does not need to be called.
	 *
	 * @param Client $connection
	 */
	public function setConnection(Client $connection): void
	{
		$this->cs->mongoClient = $connection;
	}

	/**
	 * Get MongoDB instance
	 *
	 * @return Database Mongo DB instance
	 * @throws ManganException
	 */
	public function getDbInstance(): Database
	{
		if ($this->cs->mongoDB === null)
		{
			if (!$this->dbName)
			{
				throw new ManganException(sprintf("Database name (`dbName`) is required for connectionId: `%s`", $this->connectionId), ExceptionCodeInterface::RequireDbName);
			}
			try
			{
				$this->cs->mongoDB = $this->getConnection()->selectDatabase($this->dbName);
			}
			catch (Exception $e)
			{
				throw new ManganException(sprintf('Could not select db name: `%s`, for connectionId: `%s` - %s', $this->dbName, $this->connectionId, $e->getMessage()), ExceptionCodeInterface::CouldNotSelect, $e);
			}
		}
		return $this->cs->mongoDB;
	}

	/**
	 * Set MongoDB instance by supplying database name.
	 *
	 * Use this to select db from external source.
	 * In most scenarios this does not need to be called.
	 *
	 * @param string $name
	 * @throws ManganException
	 */
	public function setDbInstance(string $name): void
	{
		$this->dbName = $name;
		$this->cs->mongoDB = $this->getConnection()->selectDatabase($name);
	}

	/**
	 * Closes the currently active Mongo connection.
	 * It does nothing if the connection is already closed.
	 */
	protected function close(): void
	{
		if ($this->cs->mongoClient !== null)
		{
			$this->cs->mongoClient->close();
			$this->cs->mongoClient = null;
			$this->cs->mongoDB = null;
		}
	}

	/**
	 * Change working database
	 * @param $name
	 */
	public function selectDb($name): void
	{
		$this->setDbInstance($name);
	}

	/**
	 * Drop current database
	 */
	public function dropDb(): void
	{
		$this->getDbInstance()->drop();
	}

}
