<?php

namespace Connections;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Mangan;
use Maslosoft\ManganTest\Models\ActiveDocument\DocumentBaseAttributes;
use Maslosoft\ManganTest\Models\ModelWithLabel;
use MongoDB;
use MongoId;
use UnitTester;

class SelectDbTest extends Test
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
	public function testIfWillAllowSelectingDB()
	{
		// Default
		$mangan = Mangan::fly();

		$mangan->selectDB(ManganForthDbName);

		$model = new DocumentBaseAttributes;
		$model->_id = new MongoId;
		$model->string = 'test';
		$em = new EntityManager($model);
		$saved = $em->save();

		$this->assertTrue($saved, 'Model was saved');

		$collections = $mangan->getDbInstance()->listCollections();
		codecept_debug($collections);

		$mangan2 = Mangan::fly('four');
		$collections = $mangan2->getDbInstance()->listCollections();
		codecept_debug($collections);
		$finder = new Finder($model, null, $mangan2);
		$count = $finder->count();
		$this->assertSame(1, $count, 'That model was saved to other database');
	}

}
