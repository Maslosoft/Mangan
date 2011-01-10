<?php
/**
 * EMongoPartialDocument.php
 *
 * PHP version 5.2+
 *
 * @author		Nagy Attila Gabor
 * @author		Dariusz Górecki <darek.krk@gmail.com>
 * @copyright	2011 CleverIT http://www.cleverit.com.pl
 * @license		http://www.yiiframework.com/license/ BSD license
 * @version		1.3
 * @category	ext
 * @package		ext.YiiMongoDbSuite
 *
 */

/**
 * EMongoPartialDocument
 *
 * @property-read boolean $isPartial
 */
abstract class EMongoPartialDocument extends EMongoDocument
{
	protected $_dirtyFields = array(); // Fields that have been loaded and thus needs update
	protected $_isPartial = false; // Whatever the document has been partially loaded

	/**
	 * Returns if this document is only partially loaded
	 * @return boolean true if the document is partially loaded
	 */
	public function getIsPartial()
	{
		return $this->_isPartial;
	}

	/**
	 * Finds a single EMongoDocument with the specified condition.
	 * @param array|EMongoCriteria $condition query criteria.
	 *
	 * If an array, it is treated as the initial values for constructing a {@link EMongoCriteria} object;
	 * Otherwise, it should be an instance of {@link EMongoCriteria}.
	 *
	 * @return EMongoDocument the record found. Null if no record is found.
	 */
	public function find($criteria=null)
	{
		if (is_array($criteria))
			$criteria = new EMongoCriteria($criteria);

		$ret = parent::find($criteria);

		if (!empty($criteria) && $criteria->getSelect()) {
			$ret->_isPartial = true;
			$ret->_dirtyFields = $criteria->getSelect();
		}
		return $ret;
	}

	/**
	 * Finds all documents satisfying the specified condition.
	 * See {@link find()} for detailed explanation about $condition and $params.
	 * @param array|EMongoCriteria $condition query criteria.
	 * @return array list of documents satisfying the specified condition. An empty array is returned if none is found.
	 */
	public function findAll($criteria=null)
	{
		if (is_null($criteria))
			$criteria = new EMongoCriteria(array());
		elseif (is_array($criteria))
			$criteria = new EMongoCriteria($criteria);

		$ret = parent::findAll($criteria);
		if (!$criteria->getSelect())
			return $ret;

		if($this->getUseCursor())
			return $ret; // TODO: FIXME! Documents won't have the partial flag this way!
		else {
			foreach ($ret as &$obj) {
				$obj->_isPartial = true;
				$obj->_dirtyFields = $criteria->getSelect();
			}
		}
	}

	/**
	 * Updates the row represented by this active record.
	 * All loaded attributes will be saved to the database.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * @param array $attributes list of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @return boolean whether the update is successful
	 * @throws CException if the record is new
	 * @throws EMongoException on fail of update
	 * @throws MongoCursorException on fail of update, when safe flag is set to true
	 * @throws MongoCursorTimeoutException on timeout of db operation , when safe flag is set to true
	 */
	public function update(array $attributes=null)
	{
		if ($this->_isPartial) {
			$attributes = $attributes ? array_intersect($attributes, $this->_dirtyFields) : $this->_dirtyFields;
			return parent::update($attributes, true);
		}
		return parent::update($attributes);
	}
}
