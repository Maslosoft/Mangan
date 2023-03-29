<?php

namespace EntityManager;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Exceptions\BadAttributeException;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class UpdateTest extends Unit
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
	public function testIfWillUpdateWholeDocument()
	{
		$model = new ModelWithI18N();
		$model->_id = new MongoId;
		$model->active = true;
		$model->title = 'foo';

		$em = new EntityManager($model);
		$finder = new Finder($model);

		$em->save();

		$found = $finder->findByPk($model->_id);

		$this->assertSame($model->title, $found->title);
		$this->assertSame($model->active, $found->active);

		$em = new EntityManager($found);
		$found->title = 'bar';
		$em->update();

		$updated = $finder->findByPk($model->_id);

		$this->assertSame($found->title, $updated->title);
		$this->assertSame($model->active, $found->active);
	}

	public function testIfWillUpdateByModifyDocument()
	{
		$model = new ModelWithI18N();
		$model->_id = new MongoId;
		$model->active = true;
		$model->title = 'foo';

		$em = new EntityManager($model);
		$finder = new Finder($model);

		$em->save();

		$found = $finder->findByPk($model->_id);

		$this->assertSame($model->title, $found->title);
		$this->assertSame($model->active, $found->active);

		$em = new EntityManager($found);
		$found->title = 'bar';

		// This attribute should be ignored
		$found->active = false;

		$em->update(['title'], true);

		$updated = $finder->findByPk($model->_id);

		$this->assertSame($found->title, $updated->title);
		$this->assertSame($model->active, $updated->active);
	}

	public function testIfWillFailToUpdateByModifyDocumentWhenPassedNonExistentAttribute()
	{
		$model = new ModelWithI18N();
		$model->_id = new MongoId;
		$model->active = true;
		$model->title = 'foo';

		$em = new EntityManager($model);
		$finder = new Finder($model);

		$em->save();

		$found = $finder->findByPk($model->_id);

		$this->assertSame($model->title, $found->title);
		$this->assertSame($model->active, $found->active);

		$em = new EntityManager($found);
		$found->title = 'bar';

		// This attribute should be ignored
		$found->active = false;

		try
		{
			$em->update(['title', 'bogusAttribute'], true);
			$this->assertTrue(false, 'That exception was thrown');
		}
		catch (BadAttributeException $exc)
		{
			codecept_debug($exc->getMessage());
			$this->assertTrue(true, 'That exception was thrown');
		}

		try
		{
			$em->update(['bogusAttribute'], true);
			$this->assertTrue(false, 'That exception was thrown');
		}
		catch (BadAttributeException $exc)
		{
			codecept_debug($exc->getMessage());
			$this->assertTrue(true, 'That exception was thrown');
		}
	}

}
