<?php

namespace EntityManager;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Modifier;
use Maslosoft\ManganTest\Models\ActiveDocument\DocumentBaseAttributes;
use UnitTester;

class FindAndModifyTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testIfWillModify(): void
	{
		$model = new DocumentBaseAttributes();
		$model->bool = false;
		$model->string = 'Las Vegas';
		$saved = $model->save();

		$model->string = 'New';

		$this->assertTrue($saved);

		$em = new EntityManager($model);

		$criteria = PkManager::prepareFromModel($model);

		// With true
		$modifier = new Modifier([
			'int' => [
				'set' => 1
			]
		]);

		$modified = $em->findAndModify($criteria, $modifier, true);

		$this->assertNotEmpty($modified);

		$this->assertInstanceOf(DocumentBaseAttributes::class, $modified);

		$this->assertSame(1, $modified->int);

		$this->assertSame('Las Vegas', $modified->string, 'That `string` attribute was not changed');


		$finder = new Finder($model);

		$found = $finder->findByPk(PkManager::getFromModel($model));

		$this->assertNotEmpty($found);

		$this->assertSame(1, $found->int, 'That `string` attribute was really updated');
	}

	public function testIfWillNotFailModifyOnNotFoundDocument(): void
	{
		$model = new DocumentBaseAttributes();

		$em = new EntityManager($model);

		$criteria = PkManager::prepareFromModel($model);

		// With true
		$modifier = new Modifier([
			'int' => [
				'set' => 1
			]
		]);

		$modified = $em->findAndModify($criteria, $modifier, true);

		$this->assertNull($modified);
	}
}
