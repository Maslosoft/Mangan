<?php

namespace EntityManager;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use Maslosoft\ManganTest\Models\Plain\PlainWithBasicAttributes;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class PlainTest extends Unit
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
	public function testIfWillSavePlainDocument()
	{
		$model = new PlainWithBasicAttributes();
		$model->_id = new MongoId;
		$em = new EntityManager($model);

		$em->save();

		$finder = new Finder($model);

		$found = $finder->findByPk($model->_id);

		$this->assertInstanceOf(PlainWithBasicAttributes::class, $found);
	}

	public function testIfWillDeletePlainDocument()
	{
		$model = new PlainWithBasicAttributes();
		$model->_id = new MongoId;
		$em = new EntityManager($model);

		$em->save();

		$finder = new Finder($model);

		$found = $finder->findByPk($model->_id);

		$this->assertInstanceOf(PlainWithBasicAttributes::class, $found);

		$deleted = $em->delete();

		$this->assertTrue($deleted, 'Document was deleted');
		$notFound = $finder->findByPk($model->_id);

		$this->assertNull($notFound, 'Document was not found');

		$deleted = $em->delete();

		$this->assertFalse($deleted, 'Removing failed');
	}

	public function testIfWillDeletePlainDocumentByPk()
	{
		$model = new PlainWithBasicAttributes();
		$model->_id = new MongoId;
		$em = new EntityManager($model);

		$em->save();

		$finder = new Finder($model);

		$found = $finder->findByPk($model->_id);

		$this->assertInstanceOf(PlainWithBasicAttributes::class, $found);

		$em->deleteByPk($model->_id);

		$notFound = $finder->findByPk($model->_id);

		$this->assertNull($notFound);
	}

	public function testIfWillDeleteAllPlainDocumentsByPk()
	{
		$pks = [];
		$model = new PlainWithBasicAttributes();
		$model->_id = new MongoId;
		$pks[] = $model->_id;
		$em = new EntityManager($model);
		$em->save();

		$model = new PlainWithBasicAttributes();
		$model->_id = new MongoId;
		$pks[] = $model->_id;
		$em = new EntityManager($model);
		$em->save();

		$model = new PlainWithBasicAttributes();
		$model->_id = new MongoId;
		$em = new EntityManager($model);
		$em->save();



		$finder = new Finder($model);

		$count = $finder->count();

		$this->assertSame(3, $count);

		$em->deleteAllByPk($pks);

		$found = $finder->findByPk($model->_id);

		$this->assertInstanceOf(PlainWithBasicAttributes::class, $found);

		$countAfterDelete = $finder->count();

		$this->assertSame(1, $countAfterDelete);
	}

	public function testIfWillDeleteAllPlainDocument()
	{
		$model = new PlainWithBasicAttributes();
		$model->_id = new MongoId;
		$em = new EntityManager($model);

		$em->save();

		$model = new PlainWithBasicAttributes();
		$model->_id = new MongoId;
		$em = new EntityManager($model);

		$em->save();



		$finder = new Finder($model);

		$count = $finder->count();

		$this->assertSame(2, $count);

		$em->deleteAll();

		$deletedCount = $finder->count();

		$this->assertSame(0, $deletedCount);
	}

	public function testIfWillRefreshPlainDocument()
	{
		$model = new PlainWithBasicAttributes();
		$model->_id = new MongoId;
		$model->string = 'title';
		$em = new EntityManager($model);

		$em->save();

		$model->string = 'another';

		$refreshed = $em->refresh();

		$this->assertTrue($refreshed);

		$this->assertSame('title', $model->string);

		$em->deleteAll();

		$notRefreshed = $em->refresh();

		$this->assertFalse($notRefreshed);
	}

	public function testIfWillRefreshModelWithI18N()
	{
		$model = new ModelWithI18N();
		$model->_id = new MongoId;
		$model->title = 'title';
		$em = new EntityManager($model);

		$em->save();

		$model->title = 'another';

		$refreshed = $em->refresh();

		$this->assertTrue($refreshed);

		$this->assertSame('title', $model->title);

		$em->deleteAll();

		$notRefreshed = $em->refresh();

		$this->assertFalse($notRefreshed);
	}
}
