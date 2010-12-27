<?php

require_once dirname(__FILE__).'/testModels/NamedScopesModel.php';

class NamedScopesTest extends CTestCase
{
	public function testDefaultScope()
	{
		$modelWithoutDS = new BasicOperationsModel();
		$modelWithDS = new NamedScopesModel();

		$this->assertEquals(
			array(),
			$modelWithoutDS->getDbCriteria()->getConditions()
		);
		$this->assertEquals(
			array('field1'=>array('$gt'=>1)),
			$modelWithDS->getDbCriteria()->getConditions()
		);
	}

	public function testScope()
	{
		$modelWithoutDS = new BasicOperationsModel();
		$modelWithDS = new NamedScopesModel();

		try
		{
			$modelWithoutDS->scope()->getDbCriteria()->getConditions();

			$this->fail('Exception expected!');
		}
		catch(Exception $e)
		{
			$this->assertEquals(
				'Property "BasicOperationsModel.scope" is not defined.',
				$e->getMessage()
			);
		}

		$this->assertEquals(
			array('field1'=>array('$gt'=>1), 'field2'=>array('$lte'=>2)),
			$modelWithDS->scope()->getDbCriteria()->getConditions()
		);
	}

	public function testParamScope()
	{
		$modelWithDS = new NamedScopesModel();

		$this->assertEquals(
			array('field1'=>'testParam'),
			$modelWithDS->paramScope('testParam')->getDbCriteria()->getConditions()
		);
	}

	public function testChain()
	{
		$model = new NamedScopesModel();

		$this->assertEquals(
			array('field1'=>'testParam', 'field2'=>array('$lte'=>2)),
			$model->paramScope('testParam')->scope()->getDbCriteria()->getConditions()
		);
	}
}