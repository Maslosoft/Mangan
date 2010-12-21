<?php

class EMongoCriteriaTest extends CTestCase
{
	public $criteria;

	public static $TEST_ARRAY = array(
		'conditions'=>array(
			'testField1'=>array('==' => 10),
			'testField2'=>array(
				'>=' => 10,
				'%' => array(10, 0)
			),
			'testField3.testField1'=>array(
				'lesseq'=>100,
				'greater'=>10,
			),
			'testField4.testField1'=>array('==' => 20)
		),
		'sort'=>array(
			'testField1'=>EMongoCriteria::SORT_ASC,
			'testField2'=>EMongoCriteria::SORT_DESC
		),
		'limit'=>10,
		'offset'=>25,
		'select'=>array('testField1', 'testField2'),
	);

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

		// working fields array

		$this->assertTrue($this->criteria->getWorkingFields() === array());

		$this->criteria->setWorkingFields(array('testArray'));

		$this->assertTrue($this->criteria->getWorkingFields() === array('testArray'));
	}

	public function testEqualsOperator()
	{
		// magic __set method

		$this->criteria->testField = 'testValue1';

		$this->assertTrue($this->criteria->getConditions() === array(
			'testField'=>'testValue1'
		), 'Test magic __set method propagation to equals operator');

		$this->criteria->setConditions(array());

		// normal method, by magic __call

		$this->criteria->testField('==', 'testValue2');

		$this->assertTrue($this->criteria->getConditions() === array(
			'testField'=>'testValue2'
		), 'Test magic __call method propagation == to equals operator');

		$this->criteria->setConditions(array());

		$this->criteria->testField('equals', 'testValue3');

		$this->assertTrue($this->criteria->getConditions() === array(
			'testField'=>'testValue3'
		), 'Test magic __call method propagation "equals" to equals operator');

		$this->criteria->setConditions(array());

		$this->criteria->testField('eq', 'testValue4');

		$this->assertTrue($this->criteria->getConditions() === array(
			'testField'=>'testValue4'
		), 'Test magic __call method propagation "equals" to equals operator');

		$this->criteria->setConditions(array());
	}

	public function testBasicOperators()
	{
		$i = 1;
		foreach(EMongoCriteria::$operators as $opName => $opValue)
		{
			if(in_array($opValue, array(
				'$gt', '$gte', '$lt', '$lte', '$ne', '$size', '$type', '$mod',
				'$in', '$nin', '$all', '$ememMatch'
			)))
			{
				// clear conditions
				$this->criteria->setConditions(array());

				$this->criteria->testField($opName, 'testValue'.$i);

				$this->assertEquals(
					array(
						'testField'=>array(
							$opValue => 'testValue'.$i
						),
					),
					$this->criteria->getConditions(),
					'Test operator: '.$opName.' with value: testValue'.$i
				);

				$i++;
			}
		}
	}

	public function testExistsOperators()
	{
		$this->criteria->testField('exists');

		$this->assertEquals(
			array(
				'testField'=>array(
					'$exists' => true
				),
			),
			$this->criteria->getConditions(),
			'Test operator: exists'
		);

		$this->criteria->testField('notexists');

		$this->assertEquals(
			array(
				'testField'=>array(
					'$exists' => false
				),
			),
			$this->criteria->getConditions(),
			'Test operator: exists'
		);
	}

	public function testEmbeddedFieldsConditions()
	{
		$this->assertEquals(
			array(
				'testField1.testField2' => array('$gte'=>'testValue1')
			),
			$this->criteria->testField1->testField2('>=', 'testValue1')->getConditions()
		);

		$this->assertEquals(
			array(
				'testField1.testField2' => 'testValue2'
			),
			$this->criteria->testField1->testField2('==', 'testValue2')->getConditions()
		);

		$this->criteria->testField1->testField2 = 'testValue3';

		$this->assertEquals(
			array(
				'testField1.testField2' => 'testValue3'
			),
			$this->criteria->getConditions()
		);

		$this->assertEquals(
			array('testField1', 'testField2'),
			$this->criteria->testField1->testField2->getWorkingFields()
		);
	}

	public function testOperatorAggregation()
	{
		$this->criteria->testField('>=', 10);

		$this->assertEquals(
			array(
				'testField'=>array(
					'$gte'=>10
				),
			),
			$this->criteria->getConditions()
		);

		$this->criteria->testField('<=', 100);

		$this->assertEquals(
			array(
				'testField'=>array(
					'$gte'=>10,
					'$lte'=>100,
				),
			),
			$this->criteria->getConditions()
		);

		$this->criteria->testField('%', array(10, 0));

		$this->assertEquals(
			array(
				'testField'=>array(
					'$gte'=>10,
					'$lte'=>100,
					'$mod'=>array(10, 0),
				),
			),
			$this->criteria->getConditions()
		);

		$this->criteria->testField = 20;

		$this->assertEquals(
			array(
				'testField'=>20,
			),
			$this->criteria->getConditions()
		);
	}

	public function testCreationFromArray()
	{
		$testArray = self::$TEST_ARRAY;
		$criteria = new EMongoCriteria($testArray);

		// ensure that var has not been changed by any reference
		$testArray = self::$TEST_ARRAY;

		$this->assertEquals(
			array(
				'testField1' => 10,
				'testField2' => array(
					'$gte' => 10,
					'$mod' => array(10, 0),
				),
				'testField3.testField1' => array(
					'$lte' => 100,
					'$gt' => 10
				),
				'testField4.testField1' => 20,
			),
			$criteria->getConditions()
		);

		$this->assertEquals(
			$testArray['sort'],
			$criteria->getSort()
		);

		$this->assertEquals(
			$testArray['limit'],
			$criteria->getLimit()
		);

		$this->assertEquals(
			$testArray['select'],
			$criteria->getSelect()
		);

		$this->assertEquals(
			$testArray['sort'],
			$criteria->getSort()
		);
	}
}