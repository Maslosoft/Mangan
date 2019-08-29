<?php namespace DbRef;

use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Transformers\JsonArray;
use Maslosoft\ManganTest\Models\DbRef\ModelWithNullableAndUpdatableDbRef;
use Maslosoft\ManganTest\Models\DbRef\ModelWithNullableDbRef;
use Maslosoft\ManganTest\Models\Plain\SimplePlainDbRef;
use Mongo;
use MongoId;
use function var_export;

class NullableTest extends \Codeception\Test\Unit
{
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before()
	{
	}

	protected function _after()
	{
	}

	// tests
	public function testNullableModel()
	{
		$model = new ModelWithNullableDbRef;
		$em = new EntityManager($model);
		$finder = new Finder($model);
		$saved = $em->save();
		$this->assertTrue($saved);

		$this->assertNull($model->stats, 'Field is still null');

		$found = $finder->find();
		$this->assertNotEmpty($found, 'Found model');
		$this->assertInstanceOf(ModelWithNullableDbRef::class, $found);
		$this->assertNull($found->stats);
	}

	public function testNullableModelFromArray()
	{
		$model = new ModelWithNullableDbRef;
		$model->_id = new MongoId('5d67fb985dc5fa2ed93d59c4');
		$model2 = new SimplePlainDbRef;
		$model2->_id = new MongoId('5d67fb985dc5fa2ed93d59c3');
		$saved2 = (new EntityManager($model2))->save();
		$this->assertTrue($saved2);
		$model->stats = $model2;
		$em = new EntityManager($model);
		$finder = new Finder($model);
		$saved = $em->save();
		$this->assertTrue($saved);

		$this->assertInstanceOf(SimplePlainDbRef::class, $model->stats, 'Field is saved');

		$found = $finder->find();
		$this->assertNotEmpty($found, 'Found model');
		$this->assertInstanceOf(ModelWithNullableDbRef::class, $found);
		$this->assertInstanceOf(SimplePlainDbRef::class, $found->stats);

		codecept_debug(var_export(JsonArray::fromModel($model), true));
		$data = [
			'_id' => '5d67fb985dc5fa2ed93d59c4',
			'stats' => null,
			'_class' => 'Maslosoft\\ManganTest\\Models\\DbRef\\ModelWithNullableDbRef',
		];

		$model3 = JsonArray::toModel($data, null, $found);
		$this->assertNull($model3->stats, 'After setting from JSON field is null');

		$saved3 = (new EntityManager($model3))->save();
		$this->assertTrue($saved3, 'Model was saved after setting field to null');
		$this->assertNull($model3->stats, 'After setting from JSON and saving field is null');

		$found3 = (new Finder($model3))->find();
		$this->assertNotEmpty($found3);
		$this->assertNull($found3->stats, 'After finding field is still null');
	}

	public function testNullableAndUpdatableModel()
	{
		$this->markTestSkipped("The nullable and updatable feature is not implemented");
		$model = new ModelWithNullableAndUpdatableDbRef();
		$model2 = new SimplePlainDbRef;
		$model2->name = 'xxx';
		$model->stats = $model2;
		$em = new EntityManager($model);
		$em2 = new EntityManager($model2);
		$finder = new Finder($model);
		$finder2 = new Finder($model2);
		$saved2 = $em2->save();
		$this->assertTrue($saved2);

		$saved = $em->save();
		$this->assertTrue($saved);

		$this->assertInstanceOf(SimplePlainDbRef::class, $model->stats);

		$found = $finder->find();
		$this->assertNotEmpty($found, 'Found model');
		$this->assertInstanceOf(ModelWithNullableAndUpdatableDbRef::class, $found);
		$this->assertInstanceOf(SimplePlainDbRef::class, $found->stats);

		$model->stats = null;
		$saved3 = $em->save();
		$this->assertTrue($saved3);
		$this->assertNull($model->stats);
		$found2 = $finder->find();

		$this->assertNotEmpty($found2, 'Found model');
		$this->assertInstanceOf(ModelWithNullableAndUpdatableDbRef::class, $found2);
		$this->assertNull($found2->stats);
	}
}