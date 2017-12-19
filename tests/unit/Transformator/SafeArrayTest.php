<?php

namespace Transformator;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Transformers\SafeArray;
use Maslosoft\ManganTest\Models\ModelWithUnsafeAttribute;
use UnitTester;

class SafeArrayTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillSkipUnsafeAttribute()
	{
		$model = new ModelWithUnsafeAttribute();
		$model->active = false;

		$data = [
			'active' => true
		];
		SafeArray::toModel($data, null, $model);

		$this->assertFalse($model->active, 'That stats was not set while mass setting attributes');
	}

	public function testIfWillSkipUnsafeAttributeOnSaveWhenUpdatingModel()
	{
		$model = new ModelWithUnsafeAttribute();
		$model->active = true;

		$em = new EntityManager($model);
		$em->save();

		$finder = new Finder($model, $em);
		$found = $finder->findByPk($model->_id);

		$this->assertNotNull($found, 'That model was saved');

		$this->assertTrue($found->active, 'That value was set');

		// Update model from external data
		// NOTE: Creating model from external data will not work, as there is no way to take value from
		$data = [
			'active' => false
		];
		$model2 = SafeArray::toModel($data, null, $found);

		$this->assertTrue($model2->active, 'That value was ignored on mass set, as it is unsafe');

		$em2 = new EntityManager($model2);
		$em2->save();

		$found2 = $finder->findByPk($found->_id);
		$this->assertTrue($found2->active, 'That value was not updated in db');

		$this->assertSame(1, $finder->count(), 'That only one model was saved');
	}

}
