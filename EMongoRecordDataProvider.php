<?php

class EMongoRecordDataProvider extends CDataProvider
{
	/**
	 * @var string the name of key field. Defaults to '_id', as a mongo default document primary key.
	 */
	public $keyField='_id';

	/**
	 * @var string the primary ActiveRecord class name. The {@link getData()} method
	 * will return a list of objects of this class.
	 */
	public $modelClass;

	/**
	 * @var EMongoRecord the AR finder instance (e.g. <code>Post::model()</code>).
	 * This property can be set by passing the finder instance as the first parameter
	 * to the constructor.
	 */
	public $model;

	private $_query;

	/**
	 * Constructor.
	 * @param mixed $modelClass the model class (e.g. 'Post') or the model finder instance
	 * (e.g. <code>Post::model()</code>, <code>Post::model()->published()</code>).
	 * @param array $query query array witch will be passed to MongoDB collection find() method
	 * @param array $config configuration (name=>value) to be applied as the initial property values of this class.
	 */
	public function __construct($modelClass, $query = array(), $config = array())
	{
		if(is_string($modelClass))
		{
			$this->modelClass = $modelClass;
			$this->model = EMongoRecord::model($modelClass);
		}
		else if($modelClass instanceof EMongoRecord)
		{
			$this->modelClass = get_class($modelClass);
			$this->model = $modelClass;
		}

		$this->_query = $query;

		$this->setId($this->modelClass);
		foreach($config as $key=>$value)
			$this->$key=$value;
	}

	/**
	 * Returns the query criteria.
	 * @return array the query criteria
	 */
	public function getQuery()
	{
		return $this->_query;
	}

	/**
	 * Sets the query criteria.
	 * @param array $value the query criteria. Array representing the MongoDB query criteria.
	 */
	public function setQuery(array $query)
	{
		$this->_query = $query;
	}

	/**
	 * Fetches the data from the persistent data storage.
	 * @return array list of data items
	 */
	protected function fetchData()
	{
		$criteria = array('query'=>$this->_query);
		if(($pagination=$this->getPagination())!==false)
		{
			$pagination->setItemCount($this->getTotalItemCount());

			$criteria['limit']=$pagination->getLimit();
			$criteria['offset']=$pagination->getOffset();
		}

		if(($sort=$this->getSort())!==false && ($order=$sort->getOrderBy())!='')
		{
			$sort=array();
			foreach($this->getSortDirections($order) as $name=>$descending)
			{
				$sort[$name]=$descending ? -1 : 1;
			}
			$criteria['sort']=$sort;
		}

		return $this->model->findAll($criteria);
	}

	/**
	 * Fetches the data item keys from the persistent data storage.
	 * @return array list of data item keys.
	 */
	protected function fetchKeys()
	{
		$keys = array();
		foreach($this->getData() as $i=>$data)
		{
			$keys[$i] = $data->{$this->keyField};
		}
		return $keys;
	}

	/**
	 * Calculates the total number of data items.
	 * @return integer the total number of data items.
	 */
	public function calculateTotalItemCount()
	{
		return $this->model->collection->count($this->_query);
	}

	/**
	 * Converts the "ORDER BY" clause into an array representing the sorting directions.
	 * @param string $order the "ORDER BY" clause.
	 * @return array the sorting directions (field name => whether it is descending sort)
	 */
	protected function getSortDirections($order)
	{
		$segs=explode(',',$order);
		$directions=array();
		foreach($segs as $seg)
		{
			if(preg_match('/(.*?)(\s+(desc|asc))?$/i',trim($seg),$matches))
				$directions[$matches[1]]=isset($matches[3]) && !strcasecmp($matches[3],'desc');
			else
				$directions[trim($seg)]=false;
		}
		return $directions;
	}
}