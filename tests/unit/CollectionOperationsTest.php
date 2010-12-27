<?php

require_once dirname(__FILE__).'/testModels/BasicOperationsModel.php';

class CollectionOperationsTest extends CTestCase
{
	public function testGetSetCollection()
	{
		$this->assertTrue(BasicOperationsModel::model()->getCollection() instanceof MongoCollection);
		$this->assertEquals('testCollection', BasicOperationsModel::model()->getCollection()->getName());

		$newCollection = Yii::app()->getComponent('mongodb')->getDbInstance()->newCollection;

		BasicOperationsModel::model()->setCollection($newCollection);

		$this->assertTrue(BasicOperationsModel::model()->getCollection() instanceof MongoCollection);
		$this->assertEquals('newCollection', BasicOperationsModel::model()->getCollection()->getName());
	}


}