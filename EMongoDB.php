<?php

/**
 * EMongoDB
 *
 * This is merge work of tyohan, Alexander Makarov and mine
 *
 * @version 0.9
 * @author Dariusz Górecki <darek.krk@gmail.com>
 */
class EMongoDB extends CApplicationComponent
{
	/**
     * @var string host:port
     *
     * Correct syntax is:
     * mongodb://[username:password@]host1[:port1][,host2[:port2:],...]
     *
     * @example mongodb://localhost:27017
     */
    public $connectionString;

    /**
	 * @var boolean $autoConnect whether the Mongo connection should be automatically established when
	 * the component is being initialized. Defaults to true. Note, this property is only
	 * effective when the EMongoDB object is used as an application component.
	 */
	public $autoConnect = true;

	/**
     * @var false|string $persistentConnection false for non-persistent connection, string for persistent connection id to use
     */
    public $persistentConnection = false;

    /**
     * @var string $dbName name of the Mongo database to use
     */
    public $dbName = null;

    /**
     * @var MongoDB $_mongoDb instance of MongoDB driver
     */
    private $_mongoDb;

    /**
     * @var Mongo $_mongoConnection instance of MongoDB driver
     */
	private $_mongoConnection;

	/**
	 * If set to TRUE all internal DB operations will use FSYNC flag with data modification requests
	 *
	 * Generally you should whant to have this set to TRUE, exception from this is when You do massive
	 * inserts/updates/deletes with this set to TRUE they will be horribly slow
	 *
	 * @var boolean $fsyncFlag state of FSYNC flag to use with internal connections
	 */
	public $fsyncFlag=true;

	/**
	 * Connect to DB if connection is already connected this method doeas nothing
	 */
	public function connect()
	{
		if(!$this->getConnection()->connected)
			return $this->getConnection()->connect();
	}

	/**
	 * Returns Mongo connection instance if not exists will create new
	 *
	 * @return Mongo
	 * @throws EMongoException
	 */
	public function getConnection()
	{
		if($this->_mongoConnection === null)
		{
			try
			{
				Yii::trace('Opening MongoDB connection', 'ext.MongoDb.EMongoDB');
				if(empty($this->connectionString))
					throw new EMongoException(Yii::t('yii', 'EMongoDB.connectionString cannot be empty.'));

				$this->_mongoConnection = new Mongo($this->connectionString, array(
					'connect'=>$this->autoConnect,
					'persist'=>$this->persistentConnection
				));

				return $this->_mongoConnection;
			}
			catch(MongoConnectionException $e)
			{
				throw new EMongoException(Yii::t(
					'yii',
					'EMongoDB failed to open connection: {error}',
					array('{error}'=>$e->getMessage())
				), $e->getCode());
			}
		}
		else
			return $this->_mongoConnection;
	}

	/**
	 * Set the connection
	 *
	 * @param Mongo $connection
	 */
	public function setConnection(Mongo $connection)
	{
		$this->_mongoConnection = $connection;
	}

	/**
	 * Get MongoDB instance
	 */
	public function getDbInstance()
	{
		if($this->_mongoDb === null)
			return $this->_mongoDb = $this->getConnection()->selectDB($this->dbName);
		else
			return $this->_mongoDb;
	}

	/**
	 * Set MongoDB instance
	 * Enter description here ...
	 * @param string $name
	 */
	public function setDbInstance($name)
	{
		$this->_mongoDb = $this->getConnection()->selectDb($name);
	}

	/**
	 * Closes the currently active Mongo connection.
	 * It does nothing if the connection is already closed.
	 */
	protected function close(){
        if($this->_mongoConnection!==null){
            $this->_mongoConnection->close();
            $this->_mongoConnection=null;
            Yii::trace('Closing MongoDB connection', 'ext.MongoDb.EMongoDB');
        }
	}

	/**
	 * If we have don't use presist connection, close it
	 */
	public function __destruct(){
        if(!$this->persistentConnection){
            $this->close();
        }
    }

    /**
     * Drop the current DB
     */
    public function dropDb()
    {
    	$this->getDb()->drop();
    }
}
