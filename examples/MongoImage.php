<?php
/**
 * @author Ianaré Sévi
 * @author Jose Martinez <jmartinez@ibitux.com>
 * @author Philippe Gaultier <pgaultier@ibitux.com>
 * @copyright 2010 Ibitux
 * @license New BSD license
 * @version 1.3
 * @category ext
 * @package ext.YiiMongoDbSuite
 */

/**
 * EMongoGridFS
 *
 * Authorization management, dispatches actions and views on the system.
 */
class MongoImage extends EMongoGridFS
{
	public $metadata;

	/**
	 * Similar to the get tableName() method.
	 * @return string The name of the document for this class.
	 */
	public function getCollectionName()
	{
		// Should be in all lowercase.
		return 'images';
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className class name
	 * @return MongoImage the static model class.
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This is defined as normal. Nothing has changed here.
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('filename, metadata','safe'),
			array('filename','required'),
		);
	}
}