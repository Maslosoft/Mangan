<?php

class EMongoCriteriaTest extends CTestCase
{
	public $criteria;

	public function setUp()
	{
		parent::setUp();

		$this->criteria = new EMongoCriteria;
	}

	public function testCriteriaObjectCreaction()
	{
		$this->assertTrue($this->criteria instanceof EMongoCriteria, 'Test criteria object creation');
	}

	public function testGetSetFields()
	{
		// conditions array

		$this->assertTrue($this->criteria->getConditions() === array());

		$this->criteria->setConditions(array('testArray'));

		$this->assertTrue($this->criteria->getConditions() === array('testArray'));

		// Limit field

		$this->assertTrue($this->criteria->getLimit() === null);

		$this->criteria->setLimit(10);

		$this->assertTrue($this->criteria->getLimit() === 10);

		// offset field

		$this->assertTrue($this->criteria->getOffset() === null);

		$this->criteria->setOffset(10);

		$this->assertTrue($this->criteria->getOffset() === 10);

		// sort array

		$this->assertTrue($this->criteria->getSort() === array());

		$this->criteria->setSort(array('testArray'));

		$this->assertTrue($this->criteria->getSort() === array('testArray'));

		// select array

		$this->assertTrue($this->criteria->getSelect() === array());

		$this->criteria->setSelect(array('testArray'));

		$this->assertTrue($this->criteria->getSelect() === array('testArray'));
	}

	public function testEqualsOperator()
	{
/*		$this->criteria->testField = 'testValue';

		$this->assertTrue($this->criteria->getConditions() === array(
			'testField'=>'testValue'
		), 'Test magic __set method propagation to equals operator');

		$this->criteria->$testField('==');*/
	}
}