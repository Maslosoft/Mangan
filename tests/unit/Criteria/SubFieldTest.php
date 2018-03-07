<?php
namespace Criteria;

use Maslosoft\Mangan\Criteria;

class SubFieldTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testSubFieldConditionsViaGetSet()
    {
		$criteria = new Criteria;
		$criteria->address->street = 'Diagonal';

		$conditions = $criteria->getConditions();

		codecept_debug($conditions);

		$this->assertCount(1, $conditions);
		$this->assertArrayHasKey('address.street', $conditions, 'That dotted syntax was created');

		$criteria = new Criteria;
		$criteria->address->city->street->number = 666;

		$conditions = $criteria->getConditions();

		codecept_debug($conditions);

		$this->assertCount(1, $conditions);
		$this->assertArrayHasKey('address.city.street.number', $conditions, 'That dotted syntax was created');
    }

	public function testSubFieldConditionsViaConstructor()
	{
		$cfg = [
			'conditions' => [
				'address.street' => ['eq' => 'Diagonal']
			]
		];
		$criteria = new Criteria($cfg);

		$conditions = $criteria->getConditions();

		codecept_debug($conditions);

		$this->assertCount(1, $conditions);
		$this->assertArrayHasKey('address.street', $conditions, 'That dotted syntax was created');

		$cfg = [
			'conditions' => [
				'address.city.street.number' => ['eq' => 'Diagonal']
			]
		];
		$criteria = new Criteria($cfg);

		$conditions = $criteria->getConditions();

		codecept_debug($conditions);

		$this->assertCount(1, $conditions);
		$this->assertArrayHasKey('address.city.street.number', $conditions, 'That dotted syntax was created');
	}

	public function testSubFieldConditionsViaConstructorAndThenGetSet()
	{
		$cfg = [
			'conditions' => [
				'address.street' => ['eq' => 'Diagonal']
			]
		];
		$criteria = new Criteria($cfg);
		$criteria->address->street = 'Diagonal';

		$conditions = $criteria->getConditions();

		codecept_debug($conditions);

		$this->assertCount(1, $conditions);
		$this->assertArrayHasKey('address.street', $conditions, 'That dotted syntax was created');

		$cfg = [
			'conditions' => [
				'address.city.street.number' => ['eq' => 'Diagonal']
			]
		];
		$criteria = new Criteria($cfg);
		$criteria->address->city->street->number = 666;

		$conditions = $criteria->getConditions();

		codecept_debug($conditions);

		$this->assertCount(1, $conditions);
		$this->assertArrayHasKey('address.city.street.number', $conditions, 'That dotted syntax was created');
	}
}