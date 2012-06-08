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
 * EMongoHttpSession
 *
 * Example, in config/main.php:
 * 	'session' => array(
 * 		'class' => 'ext.EMongoDbHttpSession',
 * 		'collectionName' => 'yiisession',
 * 		'idColumn' => 'id',
 * 		'dataColumn' => 'data',
 * 		'expireColumn' => 'expire',
 * 	),
 *
 * Options:
 * connectionID			: mongo component name			: default mongodb
 * collectionName		: collaction name				: default yiisession
 * idColumn				: id column name				: default id
 * dataColumn			: data column name				: default dada
 * expireColumn			: expire column name			: default expire
 * fsync				: fsync flag					: default false
 * safe					: safe flag						: default false
 * timeout				: timeout miliseconds			: default null
 *
 */
class EMongoHttpSession extends CHttpSession
{
	/**
	 * @var string Mongo DB component.
	 */
	public $connectionID = 'mongodb';
	/**
	 * @var string Collection name
	 */
	public $collectionName = 'yiisession';
	/**
	 * @var string id column name
	 */
	public $idColumn = 'id';
	/**
	 * @var string level data name
	 */
	public $dataColumn = 'data';
	/**
	 * @var string expire column name
	 */
	public $expireColumn = 'expire';
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
	 * @var MongoCollection mongo Db collection
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
	 * Initializes the route.
	 * This method is invoked after the route is created by the route manager.
	 */
	public function init()
	{
		$this->setCollection($this->collectionName);
		$this->_options = array(
			'fsync' => $this->fsync,
			'safe' => $this->safe
		);
		if (!is_null($this->timeout))
			$this->_options['timeout'] = $this->timeout;
		parent::init();
	}

	protected function getData($id)
	{
		return $this->_collection->findOne(array($this->idColumn => $id), array($this->dataColumn));
	}

	protected function getExipireTime()
	{
		return time() + $this->getTimeout();
	}

	/**
	 * Returns a value indicating whether to use custom session storage.
	 * This method overrides the parent implementation and always returns true.
	 * @return boolean whether to use custom storage.
	 */
	public function getUseCustomStorage()
	{
		return true;
	}

	/**
	 * Session open handler.
	 * Do not call this method directly.
	 * @param string $savePath session save path
	 * @param string $sessionName session name
	 * @return boolean whether session is opened successfully
	 */
	public function openSession($savePath, $sessionName)
	{
		$this->gcSession(0);
	}

	/**
	 * Session read handler.
	 * Do not call this method directly.
	 * @param string $id session ID
	 * @return string the session data
	 */
	public function readSession($id)
	{
		$row = $this->getData($id);
		return is_null($row) ? '' : $row[$this->dataColumn];
	}

	/**
	 * Session write handler.
	 * Do not call this method directly.
	 * @param string $id session ID
	 * @param string $data session data
	 * @return boolean whether session write is successful
	 */
	public function writeSession($id, $data)
	{
		$options = $this->_options;
		$options['upsert'] = true;
		return $this->_collection->update(
				array($this->idColumn => $id),
				array(
					$this->dataColumn => $data,
					$this->expireColumn => $this->getExipireTime(),
					$this->idColumn => $id
				),
				$options
		);
	}

	/**
	 * Session destroy handler.
	 * Do not call this method directly.
	 * @param string $id session ID
	 * @return boolean whether session is destroyed successfully
	 */
	public function destroySession($id)
	{
		return $this->_collection->remove(
						array($this->idColumn => $id), $this->_options);
	}

	/**
	 * Session GC (garbage collection) handler.
	 * Do not call this method directly.
	 * @param integer $maxLifetime the number of seconds after which data will be seen as 'garbage' and cleaned up.
	 * @return boolean whether session is GCed successfully
	 */
	public function gcSession($maxLifetime)
	{
		return $this->_collection->remove(array($this->expireColumn => array('$lt' => time())), $this->_options);
	}

	/**
	 * Updates the current session id with a newly generated one.
	 * Please refer to {@link http://php.net/session_regenerate_id} for more details.
	 * @param boolean $deleteOldSession Whether to delete the old associated session file or not.
	 * @since 1.1.8
	 */
	public function regenerateID($deleteOldSession = false)
	{
		$oldId = session_id();
		;
		parent::regenerateID(false);
		$newId = session_id();
		$row = $this->getData($oldId);
		if (is_null($row)) {
			$this->_collection->insert(array(
				$this->idColumn => $newId
				, $this->expireColumn => $this->getExipireTime()
					), $this->_options);
		}
		else if ($deleteOldSession) {
			$this->_collection->update(
					array($this->idColumn => $oldId)
					, array($this->idColumn => $newId)
					, $this->_options
			);
		}
		else {
			$row[$this->idColumn] = $newId;
			unset($row['_id']);
			$this->_collection->insert($row, $this->_options);
		}
	}

}
