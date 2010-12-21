<?php

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