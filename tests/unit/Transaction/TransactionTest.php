<?php

namespace Transaction;

use Codeception\Specify;
use Codeception\Test\Unit;
use Exception;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Transaction;
use Maslosoft\ManganTest\Models\TokuMX\ModelTransactional;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class TransactionTest extends Unit
{

	use Specify;

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testTransactions(): void
	{
		$available = false;
		try
		{
			$transaction = new Transaction(new ModelTransactional());
			$transaction->rollback();

			$available = $transaction->isAvailable();
		} catch (Exception $e)
		{
			$this->markTestSkipped("Transactions thrown exception");
		}
		$this->assertNotNull($available);

		if ($available)
		{
			codecept_debug('Transactions Available');

			$this->assertTrue(true, "That transactions are available");

			$canCommitTest = function () {
				$this->canCommitTest();
			};
			$canCommitTest->bindTo($this);
			$this->specify("That transactions can commit", $canCommitTest);

			$canRollbackTest = function () {
				$this->canRollbackTest();
			};
			$canRollbackTest->bindTo($this);
			$this->specify("That transactions can rollback", $canRollbackTest);
		}
		else
		{
			codecept_debug('Transactions are NOT Available');
			$this->assertTrue(true, "That transactions are not available");
			$this->markTestSkipped('Transactions are NOT Available');
		}
	}

	public function notAvailableTest(): void
	{
		$this->assertTrue(true);
	}

	public function availableTest()
	{
		$this->assertTrue(true);
	}

	public function canCommitTest(): void
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

	public function canRollbackTest(): void
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

}
