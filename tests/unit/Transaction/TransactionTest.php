<?php

namespace Transaction;

use Codeception\Specify;
use Codeception\Test\Unit;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Transaction;
use Maslosoft\ManganTest\Models\TokuMX\ModelTransactional;
use Maslosoft\ManganTest\Models\TokuMX\ModelTransactional2;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class TransactionTest extends Unit
{

	use Specify;

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testCommit(): void
	{
		$model = new ModelTransactional();

		$finder = new Finder($model);

		$tx = new Transaction($model);

		$model->_id = new MongoId;
		$model->title = 'blah';
		$model->save();

		$found = $finder->findByPk($model->_id);

		$this->assertInstanceOf(ModelTransactional::class, $found);

		$tx->commit();

		$found2 = $finder->findByPk($model->_id);

		$this->assertInstanceOf(ModelTransactional::class, $found2);
	}

	public function testRollback(): void
	{
		$model = new ModelTransactional();

		$finder = new Finder($model);

		$tx = new Transaction($model);

		$model->_id = new MongoId;
		$model->title = 'blah';
		$model->save();

		$found = $finder->findByPk($model->_id);

		$this->assertInstanceOf(ModelTransactional::class, $found);

		$tx->rollback();

		$found2 = $finder->findByPk($model->_id);

		$this->assertNull($found2);
	}

	public function testMultiModelTransaction(): void
	{
		$model = new ModelTransactional();
		$model2 = new ModelTransactional2();

		$finder = new Finder($model);
		$finder2 = new Finder($model2);

		$tx = new Transaction([$model, $model2]);

		$model->_id = new MongoId;
		$model->title = 'blah';
		$saved = $model->save();
		$this->assertTrue($saved, 'Model was saved');

		$model2->_id = new MongoId;
		$model2->title = 'foo';
		$saved2 = $model2->save();
		$this->assertTrue($saved2, 'Model 2 was saved');

		$found = $finder->findByPk($model->_id);

		$this->assertInstanceOf(ModelTransactional::class, $found);

		$found2 = $finder2->findByPk($model2->_id);

		$this->assertInstanceOf(ModelTransactional2::class, $found2);

		$tx->commit();

		$found = $finder->findByPk($model->_id);

		$this->assertInstanceOf(ModelTransactional::class, $found);

		$found2 = $finder2->findByPk($model2->_id);

		$this->assertInstanceOf(ModelTransactional2::class, $found2);
	}
}
