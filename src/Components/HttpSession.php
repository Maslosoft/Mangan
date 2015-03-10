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

namespace Maslosoft\Mangan\Components;

use CHttpSession;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Exceptions\ManganException;
use MongoCollection;
use MongoDate;
use MongoId;
use Yii;

/**
 * HttpSession
 *
 * Example, in config/main.php:
 * 	'session' => [
 * 		'class' => 'Maslosoft\Mangan\Components\HttpSession',
 * 		'collectionName' => 'Mangan.Session',
 * 		'idColumn' => 'id',
 * 		'dataColumn' => 'data',
 * 		'expireColumn' => 'expire',
 * 	],
 *
 * Options:
 * connectionID			: mongo component name		: default mongodb
 * collectionName			: collaction name				: default yiisession
 * idColumn					: id column name				: default id
 * dataColumn				: data column name			: default dada
 * expireColumn			: expire column name			: default expire
 * fsync						: fsync flag					: default false
 * safe						: safe flag						: default false
 * timeout					: timeout miliseconds		: default null
 *
 * @author Ianaré Sévi (merge into MongoDB)
 * @author aoyagikouhei (original author)
 */
class HttpSession extends CHttpSession
{

	/**
	 * @var string Mongo DB component.
	 */
	public $connectionID = 'mongodb';

	/**
	 * @var string Collection name
	 */
	public $collectionName = 'Mangan.Session';

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
	 * Set to false to disable
	 * @var string ip column name
	 */
	public $ipColumn = 'ip';

	/**
	 * Set to false to disable
	 * @var string browser column name
	 */
	public $browserColumn = 'browser';

	/**
	 * Set to false to disable
	 * @var string session datetime column name
	 */
	public $dateTimeColumn = 'dateTime';

	/**
	 * Set to false to disable
	 * @var string user id column name
	 */
	public $userIdColumn = 'userId';

	/**
	 * @var boolean forces the update to be synced to disk before returning success.
	 */
	public $fsync = false;

	/**
	 * @var boolean the program will wait for the database response.
	 */
	public $safe = false;

	/**
	 * @var boolean if "w" is set, this sets how long (in milliseconds) for the client to wait for a database response.
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
			if (!($db instanceof Mangan))
			{
				throw new ManganException('HttpSession.connectionID is invalid');
			}

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
		$this->_options = [
			'fsync' => $this->fsync,
			'w' => $this->safe
		];
		if (!is_null($this->timeout))
		{
			$this->_options['timeout'] = $this->timeout;
		}
		parent::init();
	}

	protected function getData($id)
	{
		return $this->_collection->findOne([$this->idColumn => $id], [$this->dataColumn]);
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
		$data = [
			$this->dataColumn => $data,
			$this->expireColumn => $this->getExipireTime(),
			$this->idColumn => $id
		];
		if($this->ipColumn)
		{
			$data[$this->ipColumn] = $_SERVER['REMOTE_ADDR'];
		}
		if($this->browserColumn)
		{
			$data[$this->browserColumn] = $_SERVER['HTTP_USER_AGENT'];
		}
		if($this->dateTimeColumn)
		{
			$data[$this->dateTimeColumn] = new MongoDate();
		}
		if($this->userIdColumn)
		{
			$data[$this->userIdColumn] = new MongoId(Yii::app()->user->id);
		}
		
		return $this->_collection->update([$this->idColumn => $id], $data, $options);
	}

	/**
	 * Session destroy handler.
	 * Do not call this method directly.
	 * @param string $id session ID
	 * @return boolean whether session is destroyed successfully
	 */
	public function destroySession($id)
	{
		return $this->_collection->remove([$this->idColumn => $id], $this->_options);
	}

	/**
	 * Session GC (garbage collection) handler.
	 * Do not call this method directly.
	 * @param integer $maxLifetime the number of seconds after which data will be seen as 'garbage' and cleaned up.
	 * @return boolean whether session is GCed successfully
	 */
	public function gcSession($maxLifetime)
	{
		return $this->_collection->remove([$this->expireColumn => ['$lt' => time()]], $this->_options);
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

		parent::regenerateID(false);
		$newId = session_id();
		$row = $this->getData($oldId);
		if (is_null($row))
		{
			$this->_collection->insert([
				$this->idColumn => $newId
				, $this->expireColumn => $this->getExipireTime()
					], $this->_options);
		}
		else if ($deleteOldSession)
		{
			$this->_collection->update(
					[$this->idColumn => $oldId]
					, [$this->idColumn => $newId]
					, $this->_options
			);
		}
		else
		{
			$row[$this->idColumn] = $newId;
			unset($row['_id']);
			$this->_collection->insert($row, $this->_options);
		}
	}

}
