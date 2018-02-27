<?php

namespace EntityManager;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\EntityManager\ModelWithCustomIdAsSecondaryKey;
use UnitTester;

class UpdateOneTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function _before()
	{

	}

	protected function _after()
	{

	}

	// tests
	public function testWillUpsertAndUpdateModelWithSameKeyAsCriteria()
	{
		$model = new ModelWithCustomIdAsSecondaryKey();
		$model->id = '123';
		$model->name = 'john';

		$em = new EntityManager($model);
		$finder = new Finder($model, $em);

		$criteria = new Criteria();
		$criteria->id = $model->id;

		$result1 = $em->updateOne($criteria);
		$this->assertTrue($result1, 'That update was successfull');

		$count = $finder->count();
		$this->assertSame(1, $count, 'That one document was inserted');

		$found = $finder->find($criteria); //found
		$this->assertSame('john', $found->name, 'That stored document has proper `name`'); //found

		$model->id = '666';
		$model->name = 'joe';
		$criteria->id = 123;
		$result2 = $em->updateOne($criteria);

		$this->assertTrue($result2, 'That second update was successfull');

		$count2 = $finder->count();
		$this->assertSame(1, $count2, 'That one document was updated, not inserted');

		$criteria->id = 666;
		$model = $finder->find($criteria);

		$this->assertNotNull($model, 'That id was in fact changed');

		$this->assertSame('joe', $model->name, 'That stored document has proper `name`');
		$this->assertSame('666', $model->id, 'That stored document has proper `id`');
	}

}
