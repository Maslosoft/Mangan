<?php

namespace Connections;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Command;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Mangan;
use Maslosoft\ManganTest\Models\ActiveDocument\DocumentBaseAttributes;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class SelectDbTest extends Unit
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
	public function testIfWillAllowSelectingDB(): void
	{
		// Default connection settings
		$mangan = Mangan::fly();

		$dbStats = (new Command(null, $mangan))->dbStats();
		$this->assertSame(ManganFirstDbName, $dbStats['db']);

		$mangan->selectDB(ManganForthDbName);

		$model = new DocumentBaseAttributes;
		$model->_id = new MongoId;
		$model->string = 'test';
		$em = new EntityManager($model);
		$saved = $em->save();

		$this->assertTrue($saved, 'Model was saved');
		$finder = new Finder($model, null, $mangan);
		$count = $finder->count();
		$this->assertSame(1, $count, 'That db contains model that was saved to first database');



		$collections = iterator_to_array($mangan->getDbInstance()->listCollections());
		$dbStats = (new Command(null, $mangan))->dbStats();
		$this->assertSame(ManganForthDbName, $dbStats['db']);
		codecept_debug($collections);

		$mangan2 = Mangan::fly('four');
		$collections = iterator_to_array($mangan2->getDbInstance()->listCollections());
		codecept_debug($collections);
		$finder = new Finder($model, null, $mangan2);
		$count = $finder->count();
		$this->assertSame(1, $count, 'That db contains model that was saved to other database');
	}

}
