<?php

namespace Transaction;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Transaction;
use Maslosoft\ManganTest\Models\ModelWithLabel;
use Maslosoft\ManganTest\Models\TokuMX\ModelTransactional;
use MongoId;
use UnitTester;

class TransactionTest extends Unit
{

	use \Codeception\Specify;

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testTransactions()
	{
		$transaction = new Transaction(new ModelTransactional());
		$transaction->rollback();

		$available = $transaction->isAvailable();

		$this->assertNotNull($available);

		if ($available)
		{
			codecept_debug('Transactions Available');

			$this->assertTrue("That transactions are available");

			$canCommitTest = function()
			{
				$this->canCommitTest();
			};
			$canCommitTest->bindTo($this);
			$this->specify("That transactions can commit", $canCommitTest);

			$canRollbackTest = function()
			{
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

	public function notAvailableTest()
	{
		$this->assertTrue(true);
	}

	public function availableTest()
	{
		$this->assertTrue(true);
	}

	public function canCommitTest()
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

	public function canRollbackTest()
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
