<?php

/**
 * @author aoyagikouhei (original author)
 * @author ianaré sévi (merge into EMongoDB)
 * 
 * @license New BSD License
 *
 * Install
 * Extract the release file under protected/extensions
 *
 * Example, in config/main.php:
 * 'log'=>array(
 *		'class'=>'CLogRouter',
 *		'routes'=>array(
 *			array(
 *				'class'=>'ext.EMongoDbLogRoute',
 *				'levels'=>'trace, info, error, warning',
 *				'categories' => 'system.*',
 *				'collectionName' => 'yiilog',
 *			),
 *		),
 * ),
 * 
 * Options:
 * mongo					: mongo component name			: default mongodb
 * collectionName			: collaction name				: default yiilog
 * message					: message column name			: default message
 * level					: level column name				: default level
 * category					: category column name			: default category
 * timestamp				: timestamp column name			: default timestamp
 * timestampType			: float or date					: default float
 * fsync					: fsync flag					: defalut false
 * safe						: safe flag						: defalut false
 * timeout					: timeout miliseconds			: defalut null i.e. MongoCursor::$timeout
 *
 */

/**
 * EMongoLogRoute routes log messages to MongoDB.
 */
class EMongoLogRoute extends CLogRoute
{
	/**
	 * @var string Mongo DB component.
	 */
	public $mongo = 'mongodb';

	/**
	 * @var string Collection name.
	 */
	public $collectionName = 'yiilogs';

	/**
	 * @var string timestamp type name float or date
	 */
	public $timestampType = 'float';

	/**
	 * @var integer capped collection size
	 */
	//public $collectionSize = 10000;

	/**
	 * @var integer capped collection max
	 */
	//public $collectionMax = 100;

	/**
	 * @var boolean capped collection install flag
	 */
	//public $installCappedCollection = false;

	/**
	 * @var boolean Force the update to be synced to disk before returning success.
	 */
	public $fsync = false;

	/**
	 * @var boolean The program will wait for the database response.
	 */
	public $safe = false;

	/**
	 * @var boolean If "safe" is set, this sets how long (in milliseconds) for the client to wait for a database response.
	 */
	public $timeout = null;

	/**
	 * @var array Insert options.
	 */
	private $_options;

	/**
	 * @var MongoCollection Collection object used.
	 */
	private $_collection;

	/**
	 * EMongoDB component static instance
	 * @var EMongoDB $_emongoDb;
	 * @since v1.0
	 */
	protected static $_emongoDb;

	/**
	 * Get EMongoDB component instance.
	 * By default it is mongodb application component
	 * @return EMongoDB
	 */
	public function getMongoDBComponent()
	{
		if (self::$_emongoDb === null)
			self::$_emongoDb = Yii::app()->getComponent($this->mongodb);

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
	 * @return MongoCollection
	 */
	public function getCollection()
	{
		if (!isset($this->_collection))
			$this->_collection = $this->getDb()->selectCollection($this->collectionName);

		return $this->_collection;
	}

	/**
	 * Initializes the route.
	 * This method is invoked after the route is created by the route manager.
	 */
	public function init()
	{
		parent::init();

		$this->_collection = $this->getCollection();
		$this->_options = array(
			'fsync' => $this->fsync
			, 'safe' => $this->safe
		);
		if (!is_null($this->timeout)) {
			$this->_options['timeout'] = $this->timeout;
		}
	}

	/**
	 * Processes log messages and sends them to specific destination.
	 * @param array $logs list of messages.  Each array elements represents one message
	 * with the following structure:
	 * array(
	 *   [0] => message (string)
	 *   [1] => level (string)
	 *   [2] => category (string)
	 *   [3] => timestamp (float, obtained by microtime(true));
	 */
	protected function processLogs($logs)
	{
		foreach ($logs as $log) {
			$this->_collection->insert(
				array(
					$this->message => $log[0],
					$this->level => $log[1],
					$this->category => $log[2],
					$this->timestamp => ($this->timestampType === 'date') ? new MongoDate(round($log[3])) : $log[3]
					),
				$this->_options
			);
		}
	}

}
