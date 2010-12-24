<?php
/**
 * EMongoCriteriaTest.php
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

	public function testConditionsChaining()
	{
		$this->criteria->
			testField1->testField2('%', array(10, 0))->
			testField3('==', 12)->
			sort('testField1', EMongoCriteria::SORT_ASC)->
			limit(10)->
			select(array('testField2'))->
			offset(25);

		$this->assertEquals(
			array(
				'testField1.testField2'=>array('$mod'=>array(10, 0)),
				'testField3'=>12,
			),
			$this->criteria->getConditions()
		);

		$this->assertEquals(
			array('testField1'=>EMongoCriteria::SORT_ASC),
			$this->criteria->getSort()
		);

		$this->assertEquals(
			10,
			$this->criteria->getLimit()
		);

		$this->assertEquals(
			array('testField2'),
			$this->criteria->getSelect()
		);

		$this->assertEquals(
			25,
			$this->criteria->getOffset()
		);
	}

	public function testCriteriaMerge()
	{
		$c1 = new EMongoCriteria;
		$c2 = new EMongoCriteria;

		$c1->fieldName1('>', 10)->fieldName1('<', 100);
		$c1->fieldName2 = 20;
		$c1->fieldName4->fieldName5('<', 100);
		$c1->limit(10)->offset(20)->select(array('fieldName1'))->sort('fieldName1', EMongoCriteria::SORT_ASC);

		$c2->fieldName1 = 20;
		$c2->fieldName2('%', array(10, 0));
		$c2->fieldName4->fieldName5('>', 10);
		$c2->limit(5)->offset(5)->select(array('fieldName2'))->sort('fieldName2', EMongoCriteria::SORT_DESC);

		$c1->mergeWith($c2);

		$this->assertEquals(
			array(
				'fieldName1' => 20,
				'fieldName2' => array(
					'$mod' => array(10, 0),
				),
				'fieldName4.fieldName5' => array(
					'$lt' => 100,
					'$gt' => 10,
				),
			),
			$c1->getConditions()
		);

		$this->assertEquals(
			5, $c1->getLimit()
		);

		$this->assertEquals(
			5, $c1->getOffset()
		);

		$this->assertEquals(
			array(
				'fieldName1', 'fieldName2'
			),
			$c1->getSelect()
		);

		$this->assertEquals(
			array(
				'fieldName1'=>EMongoCriteria::SORT_ASC, 'fieldName2'=>EMongoCriteria::SORT_DESC
			),
			$c1->getSort()
		);

		$c2->mergeWith(array(
			'conditions'=>array(
				'fieldName1' => array(
					'>' => 10,
					'<' => 100,
				),
				'fieldName2' => array('==' => 20),
				'fieldName4.fieldName5' => array(
					'<' => 100,
				),
				'fieldName6' => array('notExists'),
			),
			'limit' => 10,
			'offset' => 20,
			'select' => array('fieldName1'),
			'sort' => array('fieldName1' => EMongoCriteria::SORT_ASC)
		));

		$this->assertEquals(
			array(
				'fieldName1' => array(
					'$gt' => 10,
					'$lt' => 100,
				),
				'fieldName2' => 20,
				'fieldName4.fieldName5' => array(
					'$gt' => 10,
					'$lt' => 100,
				),
				'fieldName6' => array('$exists' => false)
			),
			$c2->getConditions()
		);

		$this->assertEquals(
			10, $c2->getLimit()
		);

		$this->assertEquals(
			20, $c2->getOffset()
		);

		$this->assertEquals(
			array(
				'fieldName2', 'fieldName1'
			),
			$c2->getSelect()
		);

		$this->assertEquals(
			array(
				'fieldName2'=>EMongoCriteria::SORT_DESC, 'fieldName1'=>EMongoCriteria::SORT_ASC
			),
			$c2->getSort()
		);
	}
}