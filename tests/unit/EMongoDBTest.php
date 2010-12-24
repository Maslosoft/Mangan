<?php
/**
 * EMongoDBTest.php
 *
 * PHP version 5.2+
 *
 * @author		Dariusz Górecki <darek.krk@gmail.com>
 * @author		Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright	2010 CleverIT http://www.cleverit.com.pl
 * @license		http://www.yiiframework.com/license/ BSD license
 * @version		1.3
 * @category	ext
 * @package		ext.YiiMongoDbSuite
 *
 */

class EMongoDBTest extends CTestCase
{
	public function testMongoDbComponentGet()
	{
		$db = Yii::app()->getComponent('mongodb');

		$this->assertTrue($db instanceof EMongoDB, 'Get via getComponent method');

		$db = null;

		$db = Yii::app()->mongodb;

		$this->assertTrue($db instanceof EMongoDB, 'Get via magic __get method');
	}

	public function testGetMongoConnectionObject()
	{
		$this->assertTrue(Yii::app()->mongodb->connection instanceof Mongo, 'Test connection object');
	}
}