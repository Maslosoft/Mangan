<?php

namespace UseCases;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\DataProvider;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\NonHomogenous\ModelOne;
use Maslosoft\ManganTest\Models\NonHomogenous\ModelTwo;
use MongoId;
use UnitTester;

class NonHomogeneousCollectionTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	private $model1;
	private $model2;
	private $model3;

	protected function _before()
	{
		$model1 = new ModelOne();
		$model1->_id = new MongoId();
		$model1->type = 1;
		$saved = $model1->save();
		$this->assertTrue($saved);

		$model2 = new ModelTwo();
		$model2->_id = new MongoId();
		$model2->type = 2;
		$saved = $model2->save();
		$this->assertTrue($saved);

		$model3 = new ModelTwo();
		$model3->_id = new MongoId();
		$model3->type = 3;
		$saved = $model3->save();
		$this->assertTrue($saved);

		$this->model1 = $model1;
		$this->model2 = $model2;
		$this->model3 = $model3;
	}

	// tests
	public function testStoreAndRetrieve()
	{
		$model1 = $this->model1;
		$id1 = $model1->_id;
		$model2 = $this->model2;
		$id2 = $model2->_id;

		$count = $model1->count();

		$this->assertSame(3, $count);

		$found1 = $model1->findByPk($id1);
		$exists1 = $model1->exists(new Criteria(['conditions' => ['_id' => ['==' => $id1]]]));
		$this->assertTrue($exists1);
		$this->assertTrue($found1 instanceof ModelOne);
		$this->assertSame(1, $found1->type);

		$found2 = $model2->findByPk($id2);
		$exists2 = $model1->exists(new Criteria(['conditions' => ['_id' => ['==' => $id2]]]));
		$this->assertTrue($exists2);
		$this->assertTrue($found2 instanceof ModelTwo);
		$this->assertSame(2, $found2->type);
	}

	public function testFindAll()
	{
		$finder = new Finder($this->model1);

		$data = $finder->findAll();

		$this->checkItems(3, $data);
	}

	public function testFindAllWithInOperator()
	{
		$finder = new Finder($this->model1);

		$criteria = new Criteria(null, $this->model1);

		$criteria->addCond('type', 'in', [1, 3]);

		$conds = $criteria->getConditions();

		codecept_debug($conds);

		$data = $finder->findAll($criteria);

		$this->checkItems(2, $data);
	}

	public function testDataProvider()
	{
		$dp = new DataProvider($this->model1);

		$count = $dp->getTotalItemCount();

		$this->assertSame(3, $count, 'There are 2 items - count');

		$data = $dp->getData();

		$this->checkItems(3, $data);
	}

	private function checkItems($count, $data)
	{
		$this->assertCount($count, $data, "There are $count items");

		$types = [];
		foreach($data as $item)
		{
			$key = get_class($item);

			$this->assertTrue(in_array($item->type, [1,2,3]), 'Has proper type');

			if($item instanceof ModelOne)
			{
				$types[$key] = $item;
			}
			if($item instanceof ModelTwo)
			{
				$types[$key] = $item;
			}
		}

		codecept_debug(array_keys($types));

		$this->assertCount(2, $types, 'There are 2 different item types');
	}
}
