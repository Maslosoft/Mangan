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
 * @property-read array $loadedFields
 * @property-read array $unloadedFields
 */
abstract class EMongoPartialDocument extends EMongoDocument
{
	protected $_loadedFields	= array();	// Fields that have not been loaded from DB
	protected $_isPartial		= false;	// Whatever the document has been partially loaded

	/**
	 * Returns if this document is only partially loaded
	 * @return boolean true if the document is partially loaded
	 */
	public function getIsPartial()
	{
		return $this->_isPartial;
	}

	/**
	 * Returns list of fields that have been loaded from DB by
	 * {@link EMongoDocument::instantiate} method.
	 * @return array
	 */
	public function getLoadedFields()
	{
		return $this->_loadedFields;
	}

	/**
	 * Returns list of fields that have not been loaded from DB by
	 * {@link EMongoDocument::instantiate} method.
	 * @return array
	 */
	public function getUnloadedFields()
	{
		return array_diff(
			$this->_loadedFields,
			$this->attributeNames()
		);
	}

	public function loadAttributes($attributes = array())
	{
		$document = $this->getCollection()->findOne(
			array('_id' => $this->_id),
			$attributes
		);

		unset($document['_id']);

		$attributesSum = array_merge($this->_loadedFields, array_keys($document));

		if(count($attributesSum) === count($this->attributeNames()))
		{
			$this->_isPartial		= false;
			$this->_loadedFields	= null;
		}
		else
		{
			$this->_loadedFields = $attributesSum;
		}

		$this->setAttributes($document, false);
	}

	/**
	 * Updates the row represented by this active record.
	 * All loaded attributes will be saved to the database.
	 * Note, validation is not performed in this method. You may call {@link validate} to perform the validation.
	 * @param array $attributes list of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @param boolean modify if set true only selected attributes will be replaced, and not
	 * the whole document
	 * @return boolean whether the update is successful
	 * @throws CException if the record is new
	 * @throws EMongoException on fail of update
	 * @throws MongoCursorException on fail of update, when safe flag is set to true
	 * @throws MongoCursorTimeoutException on timeout of db operation , when safe flag is set to true
	 * @since v1.0
	 */
	public function update(array $attributes=null, $modify = false)
	{
		if($this->_isPartial)
		{
			$attributes = count($attributes) > 0 ? array_intersect($attributes, $this->_loadedFields) : $attributes;
			return parent::update($attributes, true);
		}

		return parent::update($attributes, $modify);
	}

	protected function instantiate($attributes)
	{
		$model = parent::instantiate($attributes);

		$loadedFields = array_keys($attributes);

		if(count($unloadedFields) < count($model->attributeNames()))
		{
			$model->_isPartial		= true;
			$model->_loadedFields	= $loadedFields;
		}

		return $model;
	}
}
