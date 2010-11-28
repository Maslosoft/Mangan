<?php

/**
 * This is a MongoDbConnection component that used for connect to mongoDb database
 *
 * @author yohan
 */
class EMongoDbConnection extends CApplicationComponent
{
	private $_dbConnection;
	private $_dbName;
	private $_user;
	private $_password;
	private $_host='localhost';

	/**
	 * If set to TRUE all internal DB operations will use FSYNC flag with data modification requests
	 *
	 * Generally you should whant to have this set to TRUE, exception from this is when You do massive
	 * inserts/updates/deletes with this set to TRUE they will be horribly slow
	 *
	 * @var boolean $fsyncFlag state of FSYNC flag to use with internal connections
	 */
	public $fsyncFlag=true;

	public function  init()
	{
		parent::init();
	}

	protected function getConnection()
	{
		if($this->_dbConnection===NULL)
		{
			try
			{
				$this->_dbConnection= new Mongo($this->_host);
				if($this->_user!==NULL && $this->_password!==NULL)
						 $this->_dbConnection->{$this->getDbName()}->authenticate($this->_user, $this->_password);
			}
			catch(MongoConnectionException $e)
			{
				throw new CDbException("Can't connect to Mongo DB");
			}
		}
		return $this->_dbConnection;
	}

	protected function setDbName($name)
	{
		$this->_dbName=$name;
	}

	protected function getDbName()
	{
		return $this->_dbName;
	}

	protected function getDb()
	{
		return $this->getConnection()->{$this->getDbName()};
	}

	protected function setUser($name)
	{
		$this->_user=$name;
	}

	protected function getUser()
	{
		return $this->_user;
	}

	protected function setPassword($pass)
	{
		$this->_password=$pass;
	}

	protected function getPassword()
	{
		return $this->_password;
	}

	protected function setHost($host)
	{
		$this->_host=$host;
	}

	protected function getHost()
	{
		return $this->_host;
	}
}
