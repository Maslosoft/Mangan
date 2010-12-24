<?php
/**
 * EMongoCursor.php
 *
 * PHP version 5.2+
 *
 * @author		Nagy Attila Gábor <nagy.attila.gabor@gmail.com>
 * @author		Dariusz Górecki <darek.krk@gmail.com>
 * @author		Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright	2010 CleverIT http://www.cleverit.com.pl
 * @license		http://www.yiiframework.com/license/ BSD license
 * @version		1.3
 * @category	ext
 * @package		ext.YiiMongoDbSuite
 *
 */

/**
 * EMongoCursor
 *
 * Cursor object, that behaves much like the MongoCursor,
 * but thisone returns instantiated objects
 */
class EMongoCursor implements Iterator
{
	/**
	 * @var MongoCursor $_cursor the MongoCursor returned by the query
	 */
	protected $_cursor;

	/**
	 * @var EMongoDocument $_model the model used for instantiating objects
	 */
	protected $_model;

	/**
	 * @var array cache for object models
	 */
	protected $_cache = array();

	/**
	 * Construct a new EMongoCursor
	 *
	 * @param MongoCursor $cursor the cursor returned by the query
	 * @param EMongoDocument $model the model for instantiating objects
	 */
	public function __construct(MongoCursor $cursor, EMongoDocument $model)
	{
		$this->_cursor	= $cursor;
		$this->_model	= $model;
	}

	/**
	 * Return MongoCursor for additional tuning
	 *
	 * @return MongoCursor the cursor used for this query
	 */
	public function getCursor()
	{
		return $this->_cursor;
	}

	/**
	 * Return the current element
	 * @return EMongoDocument
	 */
	public function current()
	{
		if(!isset($this->_cache[$this->_cursor->key()]))
		{
			$document = $this->_cursor->current();
			if(empty($document))
			{
				$this->_cache[$this->_cursor->key()] = $document;
				return $document;
			}
			$this->_cache[$this->_cursor->key()] = $this->_model->populateRecord($document);
		}

		return $this->_cache[$this->_cursor->key()];
	}

	/**
	 * Return the key of the current element
	 * @return scalar
	 */
	public function key()
	{
		return $this->_cursor->key();
	}

	/**
	 * Move forward to next element
	 * @return void
	 */
	public function next()
	{
		$this->_cursor->next();
	}

	/**
	 * Rewind the Iterator to the first element
	 * @return void
	 */
	public function rewind()
	{
		$this->_cursor->rewind();
	}

	/**
	 * Checks if current position is valid
	 * @return boolean
	 */
	public function valid()
	{
		return $this->_cursor->valid();
	}
}
