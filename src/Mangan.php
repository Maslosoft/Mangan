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

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\EmbeDi\EmbeDi;
use Maslosoft\Mangan\Decorators\DbRefArrayDecorator;
use Maslosoft\Mangan\Decorators\DbRefDecorator;
use Maslosoft\Mangan\Decorators\EmbeddedArrayDecorator;
use Maslosoft\Mangan\Decorators\EmbeddedDecorator;
use Maslosoft\Mangan\Decorators\Model\AliasDecorator;
use Maslosoft\Mangan\Decorators\Model\ClassNameDecorator;
use Maslosoft\Mangan\Decorators\Model\OwnerDecorator;
use Maslosoft\Mangan\Decorators\Property\I18NDecorator;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Helpers\ConnectionStorage;
use Maslosoft\Mangan\Interfaces\Exception\ExceptionCodeInterface;
use Maslosoft\Mangan\Interfaces\ManganAwareInterface;
use Maslosoft\Mangan\Interfaces\ProfillerInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Profillers\NullProfiller;
use Maslosoft\Mangan\Sanitizers\DateSanitizer;
use Maslosoft\Mangan\Sanitizers\DateWriteUnixSanitizer;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Mangan\Sanitizers\MongoWriteStringId;
use Maslosoft\Mangan\Transformers\CriteriaArray;
use Maslosoft\Mangan\Transformers\DocumentArray;
use Maslosoft\Mangan\Transformers\Filters\DocumentArrayFilter;
use Maslosoft\Mangan\Transformers\Filters\JsonFilter;
use Maslosoft\Mangan\Transformers\Filters\PersistentFilter;
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

	const DefaultConnectionId = 'mongodb';

	use Traits\Defaults\MongoClientOptions;

	/**
	 * Correct syntax is:
	 * mongodb://[username:password@]host1[:port1][,host2[:port2:],...]
	 * @example mongodb://localhost:27017
	 * @var string host:port
	 * @since v1.0
	 */
	public $connectionString = 'mongodb://localhost:27017';

	/**
	 * Configuration of decorators for transformers
	 * Array key is decorator class name or interface, values are decorator class names.
	 * @var string[][]
	 */
	public $decorators = [
		TransformatorInterface::class => [
			DbRefArrayDecorator::class,
			DbRefDecorator::class,
			EmbeddedArrayDecorator::class,
			EmbeddedDecorator::class,
			AliasDecorator::class,
			OwnerDecorator::class,
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
	 * Configuration for finalizers.
	 * @see https://github.com/Maslosoft/Mangan/issues/36
	 * @var string[][]
	 */
	public $finalizers = [
	];

	/**
	 * Configuration of property filters for transformers
	 * Array key is decorator class name or interface, values are filter class names.
	 * @var string[][]
	 */
	public $filters = [
		TransformatorInterface::class => [
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
	 * Sanitizers ramapping for common scenarios.
	 * @var string[][]
	 */
	public $sanitizersMap = [
		JsonArray::class => [
			MongoObjectId::class => MongoWriteStringId::class,
			DateSanitizer::class => DateWriteUnixSanitizer::class
		],
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

	/**
	 * Logger
	 * @var LoggerInterface
	 */
	private $_logger = null;

	/**
	 * Profiller
	 * @var ProfillerInterface
	 */
	private $_profiller = null;

	/**
	 * Version number holder
	 * @var string
	 */
	private static $_version = null;

	/**
	 * Instances of mangan
	 * @var Mangan[]
	 */
	private static $_mn = [];

	public function __construct($connectionId = self::DefaultConnectionId)
	{
		$this->_di = EmbeDi::fly($connectionId);
		if (!$connectionId)
		{
			$connectionId = self::DefaultConnectionId;
		}
		$this->connectionId = $connectionId;
		$this->_di->configure($this);
		$this->_cs = new ConnectionStorage($this, $connectionId);
		if (empty(self::$_mn[$connectionId]))
		{
			self::$_mn[$connectionId] = $this;
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

	/**
	 * Get mangan version
	 * @return string
	 */
	public function getVersion()
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
	 * @return Mangan
	 */
	public function setLogger(LoggerInterface $logger)
	{
		$this->_logger = $logger;
		return $this;
	}

	/**
	 * Get PSR compliant logger
	 * @return LoggerInterface
	 */
	public function getLogger()
	{
		if (null === $this->_logger)
		{
			$this->_logger = new NullLogger;
		}
		return $this->_logger;
	}

	/**
	 * Get profiller instance. This is guaranted, if not configured will return NullProfiller.
	 * @see NullProfiller
	 * @return ProfillerInterface
	 */
	public function getProfiller()
	{
		if (null === $this->_profiller)
		{
			$this->_profiller = new NullProfiller;
		}
		if ($this->_profiller instanceof ManganAwareInterface)
		{
			$this->_profiller->setMangan($this);
		}
		return $this->_profiller;
	}

	/**
	 * Set profiller instance
	 * @param ProfillerInterface $profiller
	 * @return Mangan
	 */
	public function setProfiller(ProfillerInterface $profiller)
	{
		$this->_profiller = $profiller;
		return $this;
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
		if (empty(self::$_mn[$connectionId]))
		{
			self::$_mn[$connectionId] = new static($connectionId);
		}
		return self::$_mn[$connectionId];
	}

	/**
	 * Get instance of Mangan configured for particular model
	 * @param AnnotatedInterface $model
	 * @return Mangan
	 */
	public static function fromModel(AnnotatedInterface $model)
	{
		$connectionId = ManganMeta::create($model)->type()->connectionId;
		return self::fly($connectionId);
	}

	public function init()
	{
		$this->_di->store($this);
	}

	/**
	 * Connect to DB if connection is already connected this method return connection status.
	 *
	 * @return bool Returns true if connected
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
	 * Set the connection by suppling `MongoClient` instance.
	 *
	 * Use this to set connection from external source.
	 * In most scenarios this does not need to be called.
	 *
	 * @param MongoClient $connection
	 */
	public function setConnection(MongoClient $connection)
	{
		$this->_cs->mongoClient = $connection;
	}

	/**
	 * Get MongoDB instance
	 *
	 * @return MongoDB Mongo DB instance
	 */
	public function getDbInstance()
	{
		if ($this->_cs->mongoDB === null)
		{
			if (!$this->dbName)
			{
				throw new ManganException(sprintf("Database name is required for connectionId: `%s`", $this->connectionId), ExceptionCodeInterface::RequireDbName);
			}
			try
			{
				$db = $this->getConnection()->selectDB($this->dbName);
			}
			catch (MongoException $e)
			{
				throw new ManganException(sprintf('Could not select db name: `%s`, for connectionId: `%s` - %s', $this->dbName, $this->connectionId, $e->getMessage()), ExceptionCodeInterface::CouldNotSelect, $e);
			}
			return $this->_cs->mongoDB = $db;
		}
		else
		{
			return $this->_cs->mongoDB;
		}
	}

	/**
	 * Set MongoDB instance by suppling database name.
	 *
	 * Use this to select db from external source.
	 * In most scenarios this does not need to be called.
	 *
	 * @param string $name
	 */
	public function setDbInstance($name)
	{
		$this->_cs->mongoDB = $this->getConnection()->selectDb($name);
	}

	/**
	 * Closes the currently active Mongo connection.
	 * It does nothing if the connection is already closed.
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
	 * Drop current database
	 */
	public function dropDb()
	{
		$this->getDbInstance()->drop();
	}

}
