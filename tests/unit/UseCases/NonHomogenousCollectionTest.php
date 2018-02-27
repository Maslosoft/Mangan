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

class NonHomogenousCollectionTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	private $model1;
	private $model2;

	protected function _before()
	{
		$model1 = new ModelOne();
		$model1->_id = new MongoId();
		$saved = $model1->save();
		$this->assertTrue($saved);

		$model2 = new ModelTwo();
		$model2->_id = new MongoId();
		$saved = $model2->save();
		$this->assertTrue($saved);

		$this->model1 = $model1;
		$this->model2 = $model2;
	}

	// tests
	public function testIfWillProperlyStoreAndRetrieveNonHomogenousModels()
	{
		$model1 = $this->model1;
		$id1 = $model1->_id;
		$model2 = $this->model2;
		$id2 = $model2->_id;

		$count = $model1->count();

		$this->assertSame(2, $count);

		$found1 = $model1->findByPk($id1);
		$exists1 = $model1->exists(new Criteria(['conditions' => ['_id' => ['==' => $id1]]]));
		$this->assertTrue($exists1);
		$this->assertTrue($found1 instanceof ModelOne);

		$found2 = $model2->findByPk($id2);
		$exists2 = $model1->exists(new Criteria(['conditions' => ['_id' => ['==' => $id2]]]));
		$this->assertTrue($exists2);
		$this->assertTrue($found2 instanceof ModelTwo);
	}

	public function testFindAll()
	{
		$finder = new Finder($this->model1);

		$data = $finder->findAll();

		$this->checkItems($data);
	}

	public function testDataProvider()
	{
		$dp = new DataProvider($this->model1);

		$count = $dp->getTotalItemCount();

		$this->assertSame(2, $count, 'There are 2 items - count');

		$data = $dp->getData();

		$this->checkItems($data);
	}

	private function checkItems($data)
	{
		$this->assertCount(2, $data, 'There are 2 items');

		$types = [];
		foreach($data as $item)
		{
			$key = get_class($item);
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
