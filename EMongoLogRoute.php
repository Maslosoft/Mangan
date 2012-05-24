<?php

/**
 * Auther aoyagikouhei
 *
 * 2011/08/01 ver 1.2
 * Add fsync, safe, timeout options
 *
 * 2011/07/09 ver 1.1
 * Add capped collection : Thank you joblo
 *
 * 2011/06/23 ver 1.0
 * First release
 *
 * Install
 * Extract the release file under protected/extensions
 * 
 * In config/main.php:
  'log'=>array(
  'class'=>'CLogRouter',
  'routes'=>array(
  array(
  'class'=>'ext.EMongoDbLogRoute',
  'levels'=>'trace, info, error, warning',
  'categories' => 'system.*',
  ),
  ),
  ),
 *
 * Options
 * connectionString        : host:port                      : defalut localhost:27017
 * dbName                  : database name                  : default test
 * collectionName          : collaction name                : default yiilog
 * message                 : message column name            : default message
 * level                   : level column name              : default level
 * category                : category column name           : default category
 * timestamp               : timestamp column name          : default timestamp
 * timestampType           : float or date                  : default float
 * collectionSize          : capped collection size         : default 10000
 * collectionMax           : capped collection max          : default 100
 * installCappedCollection : capped collection install flag : default false
 * fsync                   : fsync flag                     : defalut false
 * safe                    : safe flag                      : defalut false
 * timeout                 : timeout miliseconds            : defalut null i.e. MongoCursor::$timeout
 *
 * Example
  'log'=>array(
  'class'=>'CLogRouter',
  'routes'=>array(
  array(
  'class'=>'ext.EMongoDbLogRoute',
  'levels'=>'trace, info, error, warning',
  'categories' => 'system.*',
  'connectionString' => 'localhost:27017',
  'dbName' => 'test',
  'collectionName' => 'yiilog',
  'message' => 'message',
  'level' => 'level',
  'category' => 'category',
  'timestamp' => 'timestamp',
  'timestampType' => 'float',
  ,'collectionSize' => 10000
  ,'collectionMax' => 100
  ,'installCappedCollection' => true
  ),
  ),
  ),
 *
 * Capped colection
 * 1. set installCappedCollection true in main.php.
 * 2. run application and loged
 * 3. remove installCappedCollection in main.php.
 */
class EMongoLogRoute extends CLogRoute
{
	/**
	 * @var string Collection name
	 */
	public $collectionName = 'yiilogs';

	/**
	 * @var string message column name
	 */
	public $message = 'message';

	/**
	 * @var string level column name
	 */
	public $level = 'level';

	/**
	 * @var string category column name
	 */
	public $category = 'category';

	/**
	 * @var string timestamp column name
	 */
	public $timestamp = 'timestamp';

	/**
	 * @var string timestamp type name float or date
	 */
	public $timestampType = 'float';

	/**
	 * @var integer capped collection size
	 */
	public $collectionSize = 10000;

	/**
	 * @var integer capped collection max
	 */
	public $collectionMax = 100;

	/**
	 * @var boolean capped collection install flag
	 */
	public $installCappedCollection = false;

	/**
	 * @var boolean forces the update to be synced to disk before returning success.
	 */
	public $fsync = false;

	/**
	 * @var boolean the program will wait for the database response.
	 */
	public $safe = false;

	/**
	 * @var boolean if "safe" is set, this sets how long (in milliseconds) for the client to wait for a database response.
	 */
	public $timeout = null;

	/**
	 * @var array insert options
	 */
	private $_options;

	/**
	 * @var MongoCollection
	 */
	private $_collection;

	/**
	 * EMongoDB component static instance
	 * @var EMongoDB $_emongoDb;
	 * @since v1.0
	 */
	protected static $_emongoDb;
	protected static $_collections = array();

	/**
	 * Get EMongoDB component instance.
	 * By default it is mongodb application component
	 * @return EMongoDB
	 */
	public function getMongoDBComponent()
	{
		if (self::$_emongoDb === null)
			self::$_emongoDb = Yii::app()->getComponent('mongodb');

		return self::$_emongoDb;
	}

	/**
	 * Get raw MongoDB instance.
	 * @return MongoDB
	 */
	public function getDb()
	{
		return $this->getMongoDBComponent()->getDbInstance();
	}

	/**
	 * Returns current MongoCollection object.
	 * @param string $collectionName
	 * @return MongoCollection
	 */
	public function getCollection($collectionName)
	{
		if (!isset(self::$_collections[$collectionName]))
			self::$_collections[$collectionName] = $this->getDb()->selectCollection($collectionName);

		return self::$_collections[$collectionName];
	}

	/**
	 * Initializes the route.
	 * This method is invoked after the route is created by the route manager.
	 */
	public function init()
	{
		parent::init();

		$this->_collection = $this->getCollection($this->collectionName);
		$this->_options = array(
			'fsync' => $this->fsync
			, 'safe' => $this->safe
		);
		if (!is_null($this->timeout)) {
			$this->_options['timeout'] = $this->timeout;
		}
	}

	/**
	 * Saves log messages into mongodb.
	 * @param array list of log messages
	 */
	protected function processLogs($logs)
	{
		foreach ($logs as $log) {
			$this->_collection->insert(
				array(
					$this->message => $log[0],
					$this->level => $log[1],
					$this->category => $log[2],
					$this->timestamp => 'date' === $this->timestampType ? new MongoDate(round($log[3])) : $log[3]
					),
				$this->_options
			);
		}
	}

}