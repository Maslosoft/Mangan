<?php

require_once dirname(__FILE__).'/BasicTestModel.php';

class BasicOperationsModel extends BasicTestModel
{
	public $field1;
	public $field2;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}