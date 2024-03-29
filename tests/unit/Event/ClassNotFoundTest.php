<?php

namespace Event;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\NotFoundResolver;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\ManganTest\Models\Embedded\WithPlainEmbedded;
use Maslosoft\ManganTest\Models\Plain\SimplePlainEmbedded;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class ClassNotFoundTest extends Unit
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
	public function testIfWillResolveNotFoundClass()
	{
		define('BogusClass', 'SomeClass');
		$model = new WithPlainEmbedded();
		$model->_id = new MongoId;
		codecept_debug($model->_id);
		$model->stats = new SimplePlainEmbedded();

		$em = new EntityManager($model);

		$em->save();

		$pkCriteria = PkManager::prepareFromModel($model)->getConditions();
		$set = [
			'$set' => [
				'stats._class' => BogusClass
			]
		];
		$em->getCollection()->updateOne($pkCriteria, $set);

		$finder = new Finder($model);

		try
		{
			$found = $finder->findByPk($model->_id);
			$this->assertInstanceOf(WithPlainEmbedded::class, $found);
			$this->assertFalse(true, 'Expected exception was not thrown');
		}
		catch (ManganException $ex)
		{
			$this->assertTrue(true, 'Expected exception was thrown');
		}

		// Attach class not found handlers
		new NotFoundResolver($model, [
			BogusClass => SimplePlainEmbedded::class
		]);

		$found = $finder->findByPk($model->_id);
		$this->assertInstanceOf(WithPlainEmbedded::class, $found);

		$this->assertInstanceOf(SimplePlainEmbedded::class, $found->stats);
	}

}
