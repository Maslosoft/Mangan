<?php

class BaseDBTest extends CTestCase
{
	public function setUp()
	{
		parent::setUp();

		Yii::app()->mongodb->dropDb();
	}
}