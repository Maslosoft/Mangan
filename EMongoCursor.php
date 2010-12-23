<?php
/**
 * EMongoDocument.php
 *
 * PHP version 5.2+
 *
 * @author		Dariusz Górecki <darek.krk@gmail.com>
 * @author		Nagy Attila Gábor <nagy.attila.gabor@gmail.com>
 * @copyright	2010 CleverIT
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
class EMongoCursor implements Iterator, ArrayAccess, Countable
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
	  * @var array cache for array access. This guarantees, that for the
	  *      same index always the same object instance is returned
	  */
	 protected $_cache = array();

	/**
	 * Construct a new EMongoCursor
	 *
	 * @param MongoCursor $cursor the cursor returned by the query
	 * @param EMongoDocument $model the model for instantiating objects
	 */
	public function __construct(MongoCursor $cursor, EMongoDocument $model) {
		$this->_cursor = $cursor;
		$this->_model = $model;
	}

	/**
	 * Return MongoCursor for additional tuning
	 *
	 * @return MongoCursor the cursor used for this query
	 */
	public function getCursor() {
		return $this->_cursor;
	}

	/**
	 * Return the current element
	 * @return EMongoDocument
	 */
	public function current( ) {
		$document = $this->_cursor->current();
		if (empty($document)) return $document;

		return $this->_model->populateRecord($document);
	}
	
	/**
	 * Return the key of the current element
	 * @return scalar
	 */
	public function key ( ) {
		return $this->_cursor->key();
	}

	/**
	 * Move forward to next element
	 * @return void
	 */
	public function next ( ) {
		$this->_cursor->next();
	}

	/**
	 * Rewind the Iterator to the first element
	 * @return void
	 */
	public function rewind ( ) {
		$this->_cursor->rewind();
	}

	/**
	 * Checks if current position is valid
	 * @return boolean
	 */
	public function valid ( ) {
		return $this->_cursor->valid();
	}

	/**
	 * This method is executed when using the count() function on an object implementing Countable.
	 * @return integer The custom count as an integer.
	 */
	public function count ( ) {
		return $this->_cursor->count();
	}

	/**
	 * Whether a offset exists
	 * return boolean Returns TRUE on success or FALSE on failure.
	 */
	public function offsetExists  ( $offset  ) {
		if (!is_numeric($offset)) return false;
		return ($offset < $this->_cursor->count()) && ($offset >=0);
	}

	/**
	 * Returns the value at specified offset.
	 * This method is executed when checking if offset is empty().
	 * This method will reset the current cursor, and isn't really
	 * effective. Only use it with small resultsets
	 * @return EMongoDocument
	 */
	public function offsetGet ( $offset ) {
		if (!is_numeric($offset)) return null;
		if (($offset >= $this->_cursor->count()) || ($offset <0)) return null;

		if (isset($this->_cache[$offset])) {
			return $this->_cache[$offset];
		}

		// TODO: this is slooooow
		$this->_cursor->reset();
		for ($i=0; $i<=$offset; $i++) {
			$this->_cursor->next();
		}

		$this->_cache[$offset] = $this->current();
		return $this->_cache[$offset];
	}

	/**
	 * This method is not implemented: EMongoCursors are read only!
	 */
	public function offsetSet ( $offset , $value ) {
		throw new EMongoException(Yii::t('yii', 'Changing values of EMongoCursor is not allowed'));
	}

	/**
	 * This method is not implemented: EMongoCursors are read only!
	 */
	public function offsetUnset ( $offset ) {
		throw new EMongoException(Yii::t('yii', 'Changing values of EMongoCursor is not allowed'));
	}
}
