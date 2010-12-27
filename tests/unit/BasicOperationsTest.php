<?php

require_once dirname(__FILE__).'/testModels/BasicOperationsModel.php';

class BasicOperationsTest extends CTestCase
{
	public function setUp()
	{
		parent::setUp();
		BasicOperationsModel::model()->getCollection()->remove(array(), array(
			'justOne' => false
		));
	}

	public function testToArray()
	{
		$model = new BasicOperationsModel();

		$this->assertEquals(
			array('_id'=>null, 'field1'=>null, 'field2'=>null),
			$model->toArray()
		);

		$model->_id = 1;
		$model->field1 = 'test';

		$this->assertEquals(
			array('_id'=>1, 'field1'=>'test', 'field2'=>null),
			$model->toArray()
		);

		$model->_id = 'id';
		$model->field2 = 2;

		$this->assertEquals(
			array('_id'=>'id', 'field1'=>'test', 'field2'=>2),
			$model->toArray()
		);
	}

	public function testInsert()
	{
		$model = new BasicOperationsModel();

		$model->field1 = 'val1';
		$model->field2 = 1234;

		$this->assertEquals(
			BasicOperationsModel::model()->getCollection()->findOne(),
			null
		);

		$this->assertEquals(null, $model->_id);
		$this->assertTrue($model->getIsNewRecord());

		$this->assertTrue($model->save());
		$this->assertTrue($model->_id !== null);

		$this->assertFalse($model->getIsNewRecord());

		$this->assertEquals(
			BasicOperationsModel::model()->getCollection()->findOne(),
			$model->toArray()
		);
	}

	public function testUpdate()
	{
		$model = new BasicOperationsModel();

		$model->field1 = 'val1';
		$model->field2 = 1234;

		$this->assertEquals(
			BasicOperationsModel::model()->getCollection()->findOne(),
			null
		);

		$this->assertTrue($model->save());

		$model->field1 = 1234;
		$model->field2 = 'val2';

		$this->assertFalse($model->getIsNewRecord());

		$this->assertNotEquals(
			BasicOperationsModel::model()->getCollection()->findOne(),
			$model->toArray()
		);

		$this->assertTrue($model->save());
		$this->assertFalse($model->getIsNewRecord());

		$this->assertEquals(
			BasicOperationsModel::model()->getCollection()->findOne(),
			$model->toArray()
		);
	}

	public function testDelete()
	{
		$model = new BasicOperationsModel();

		$model->field1 = 'val1';
		$model->field2 = 1234;

		$this->assertEquals(
			BasicOperationsModel::model()->getCollection()->findOne(),
			null
		);

		$this->assertTrue($model->save());

		$this->assertEquals(
			BasicOperationsModel::model()->getCollection()->findOne(),
			$model->toArray()
		);

		$this->assertTrue($model->delete());

		$this->assertEquals(
			BasicOperationsModel::model()->getCollection()->findOne(),
			null
		);
	}
}