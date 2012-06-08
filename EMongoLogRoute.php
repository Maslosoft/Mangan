<?php

/**
 * @author Ianaré Sévi (merge into EMongoDB)
 * @author aoyagikouhei (original author)
 * @license New BSD license
 * @version 1.3
 * @category ext
 * @package ext.YiiMongoDbSuite
 */

/**
 * EMongoLogRoute
 *
 * Example, in config/main.php:
 * 'log'=>array(
 * 		'class' => 'CLogRouter',
 * 		'routes' => array(
 * 			array(
 * 				'class'=>'ext.EMongoDbLogRoute',
 * 				'levels'=>'trace, info, error, warning',
 * 				'categories' => 'system.*',
 * 				'collectionName' => 'yiilog',
 * 			),
 * 		),
 * ),
 *
 * Options:
 * connectionID				: mongo component name			: default mongodb
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
	public $connectionID = 'mongodb';
	/**
	 * @var string Collection name.
	 */
	public $collectionName = 'yiilogs';
	/**
	 * @var string timestamp type name: 'float', 'date', 'string'
	 */
	public $timestampType = 'float';
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
	 * Returns current MongoCollection object.
	 * @return MongoCollection
	 */
	protected function setCollection($collectionName)
	{
		if (!isset($this->_collection))
		{
			$db = Yii::app()->getComponent($this->connectionID);
			if (!($db instanceof EMongoDB))
				throw new EMongoException('EMongoHttpSession.connectionID is invalid');

			$this->_collection = $db->getDbInstance()->selectCollection($collectionName);
		}
		return $this->_collection;
	}

	/**
	 * Initialize the route.
	 * This method is invoked after the route is created by the route manager.
	 */
	public function init()
	{
		parent::init();

		$this->setCollection($this->collectionName);
		$this->_options = array(
			'fsync' => $this->fsync
			, 'safe' => $this->safe
		);
		if (!is_null($this->timeout)) {
			$this->_options['timeout'] = $this->timeout;
		}
	}

	/**
	 * Return the formatted timestamp.
	 * @param float $timestamp Timestamp as given by log function.
	 * @return mixed
	 */
	protected function formatTimestamp($timestamp)
	{
		if ($this->timestampType === 'date')
			$timestamp = new MongoDate(round($timestamp));
		else if ($this->timestampType === 'string')
			$timestamp = date('Y-m-d H:i:s', $timestamp);
		else
			$timestamp = $timestamp;
		return $timestamp;
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
			$this->_collection->insert(array(
				$this->message => $log[0],
				$this->level => $log[1],
				$this->category => $log[2],
				$this->timestamp => $this->formatTimestamp($log[3]),
					), $this->_options
			);
		}
	}

}
