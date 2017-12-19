<?php

namespace UseCases;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Criteria;
use Maslosoft\ManganTest\Models\NonHomogenous\ModelOne;
use Maslosoft\ManganTest\Models\NonHomogenous\ModelTwo;
use MongoId;
use UnitTester;

class NonHomogenousCollectionTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillProperlyStoreAndRetrieveNonHomogenousModels()
	{
		$model1 = new ModelOne();
		$id1 = $model1->_id = new MongoId();
		$model1->save();

		$model2 = new ModelTwo();
		$id2 = $model2->_id = new MongoId();
		$model2->save();

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

}
