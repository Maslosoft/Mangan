<?php

namespace EntityManager;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Exceptions\BadAttributeException;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use MongoId;
use UnitTester;

class DeleteTest extends Test
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
	public function testDeleteAll()
	{
		$this->makeModels();
		$model = new ModelWithI18N;

		$this->checkCount(10);

		$deleted = (new EntityManager($model))->deleteAll();

		$this->assertTrue($deleted, 'All deleted');

		$this->checkCount(0);
	}

	public function testDeleteAllByPk()
	{
		$models = $this->makeModels();

		$this->checkCount(10);

		$toDelete = [
			$models[0],
			$models[2],
			$models[6]
		];

		$keys = [];

		foreach($toDelete as $model)
		{
			$keys[] = PkManager::getFromModel($model);
		}

		$deleted = (new EntityManager(new ModelWithI18N))->deleteAllByPk($keys);
		$this->assertTrue($deleted, 'Models deleted');

		foreach($toDelete as $shouldBeDeleted)
		{
			$this->checkNotExists($shouldBeDeleted);
		}

		$this->checkCount(7);
	}

	public function testDeleteCurrent()
	{
		$models = $this->makeModels();

		$this->checkCount(10);

		$toDelete = $models[0];

		$deleted = (new EntityManager($toDelete))->delete();
		$this->assertTrue($deleted, 'Model deleted');

		$this->checkNotExists($toDelete);

		$this->checkCount(9);
	}

	public function testDeleteOne()
	{
		$models = $this->makeModels();

		$this->checkCount(10);

		$toDelete = $models[0];

		$deleted = (new EntityManager($toDelete))->deleteOne();
		$this->assertTrue($deleted, 'Model deleted');

		$this->checkNotExists($toDelete);

		$this->checkCount(9);
	}

	public function testDeleteOneWithCriteria()
	{
		$models = $this->makeModels();

		$this->checkCount(10);

		$toDelete = $models[0];

		$criteria = new Criteria();
		$criteria->addCond('title', '==', $toDelete->title);

		$deleted = (new EntityManager($toDelete))->deleteOne($criteria);
		$this->assertTrue($deleted, 'Model deleted');

		$this->checkNotExists($toDelete);

		$this->checkCount(9);
	}

	public function testDeleteByPk()
	{
		$models = $this->makeModels();

		$this->checkCount(10);

		$toDelete = $models[0];

		$deleted = (new EntityManager($toDelete))->deleteByPk(PkManager::getFromModel($toDelete));
		$this->assertTrue($deleted, 'Model deleted');

		$this->checkNotExists($toDelete);

		$this->checkCount(9);
	}

	private function checkNotExists($model)
	{
		$found = (new Finder(new ModelWithI18N))->findByPk(PkManager::getFromModel($model));

		$this->assertEmpty($found, 'Model not exists');
	}

	private function checkCount($num)
	{
		$count2 = (new Finder(new ModelWithI18N))->count();

		$this->assertSame($num, $count2, "Number of models is $num");
	}

	/**
	 * @return ModelWithI18N[]
	 * @throws \Maslosoft\Mangan\Exceptions\ManganException
	 */
	private function makeModels()
	{
		$models = [];
		for($i = 0; $i < 10; $i++)
		{
			$model = new ModelWithI18N();
			$model->_id = new MongoId;
			$model->active = true;
			$model->title = 'foo' . $i;

			$models[] = $model;

			$em = new EntityManager($model);
			$finder = new Finder($model);

			$saved = $em->save();

			$this->assertTrue($saved);

			$found = $finder->findByPk($model->_id);

			$this->assertSame($model->title, $found->title);
			$this->assertSame($model->active, $found->active);
		}

		return $models;
	}
}
