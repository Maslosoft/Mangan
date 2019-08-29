<?php namespace DbRef;

use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\DbRef\ModelWithNullableAndUpdatableDbRef;
use Maslosoft\ManganTest\Models\DbRef\ModelWithNullableDbRef;
use Maslosoft\ManganTest\Models\Plain\SimplePlainDbRef;

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