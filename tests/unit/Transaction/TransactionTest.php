<?php

namespace Transaction;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Transaction;
use Maslosoft\ManganTest\Models\TokuMX\ModelTransactional;
use Maslosoft\ManganTest\Models\TokuMX\ModelTransactional2;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class TransactionTest extends Unit
{


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

	public function testCommittingTwiceToEnsureThatAttemptToCreateExistingCollectionIsNotMade(): void
	{
		// Just to have 2 collections
		$model = new ModelTransactional2();
		$model->title = 'blah';
		$model->save();

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
    public function testSessionsAreScopedToTheirConnection(): void
    {
        $model = new ModelTransactional();
        $transaction = new Transaction($model);
        try {
            $own = \Maslosoft\Mangan\Mangan::fromModel($model);
            $other = \Maslosoft\Mangan\Mangan::fly('second');
            $this->assertNotNull(Transaction::getRunningSession($own));
            $this->assertNull(Transaction::getRunningSession($other));
            $this->assertFalse(Transaction::isRunning($other));
            $options = new \Maslosoft\Mangan\Options\EntityOptions($model, $other);
            $this->assertArrayNotHasKey('session', $options->getSaveOptions());
            $options = new \Maslosoft\Mangan\Options\EntityOptions($model, $own);
            $this->assertArrayHasKey('session', $options->getSaveOptions());
            $this->assertArrayNotHasKey('writeConcern', $options->getSaveOptions());
            try {
                \Maslosoft\Mangan\Mangan::reset();
                $this->fail('Resetting an active transaction must be rejected.');
            } catch (\LogicException $exception) {
                $this->assertTrue(Transaction::isRunning($own));
            }
        } finally {
            $transaction->rollback();
        }
        $this->assertNull(Transaction::getRunningSession());
    }

    public function testClassNameModelsAreAccepted(): void
    {
        $transaction = new Transaction([ModelTransactional::class]);
        $this->assertTrue(Transaction::isRunning());
        $transaction->rollback();
        $this->assertFalse(Transaction::isRunning());
    }

}
