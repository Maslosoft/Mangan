<?php

namespace EntityManager;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Modifier;
use Maslosoft\ManganTest\Models\ActiveDocument\DocumentBaseAttributes;
use UnitTester;

class ModifierTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillUpdateAll()
	{
		$model = new DocumentBaseAttributes();
		$model->bool = false;
		$model->string = 'Las Vegas';
		$model->save();

		$model = new DocumentBaseAttributes();
		$model->bool = true;
		$model->string = 'Las Palmas';
		$model->save();

		$model = new DocumentBaseAttributes();
		$model->bool = false;
		$model->string = 'Las Cruces';
		$model->save();

		$em = new EntityManager($model);



		// With true
		$modifier = new Modifier([
			'int' => [
				'set' => 1
			]
		]);

		$criteria = new Criteria();
		$criteria->bool = true;

		$ok = $em->updateAll($modifier, $criteria);
		$this->assertTrue($ok);

		$criteria = new Criteria();
		$criteria->int = 1;

		$finder = new Finder($model);
		$modified = $finder->count($criteria);
		$this->assertSame(1, $modified);

		$found = $finder->find($criteria);
		$this->assertSame(1, $found->int);

		// With false
		$modifier = new Modifier();

		$modifier->set('int', 2);

		$criteria = new Criteria();
		$criteria->bool = false;

		$ok = $em->updateAll($modifier, $criteria);
		$this->assertTrue($ok);

		$criteria = new Criteria();
		$criteria->int = 2;

		$finder = new Finder($model);
		$modified = $finder->count($criteria);
		$this->assertSame(2, $modified);

		$found = $finder->find($criteria);
		$this->assertSame(2, $found->int);
	}

	public function testIfWillUpdateOne(): void
	{
		$model = new DocumentBaseAttributes();
		$model->bool = false;
		$model->string = 'Las Vegas';
		$saved = $model->save();

		$model->string = 'New';

		$this->assertTrue($saved);

		$em = new EntityManager($model);

		$criteria = PkManager::prepareFromModel($model);

		$updated = $em->updateOne($criteria, ['bool'], true);
		$this->assertTrue($updated);

		$finder = new Finder($model);

		$found = $finder->findByPk(PkManager::getFromModel($model));

		$this->assertNotEmpty($found);

		$this->assertSame($found->string, 'Las Vegas', 'That `string` attribute was not changed');
	}
}
