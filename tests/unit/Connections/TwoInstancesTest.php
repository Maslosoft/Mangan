<?php

namespace Connections;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Mangan;
use MongoDB\Database;
use UnitTester;

class TwoInstancesTest extends Unit
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
	public function testIfTwoInstancesOfManganHaveProperConnections(): void
	{
		// Default
		$mangan = new Mangan();
		$this->assertSame($mangan->dbName, ManganFirstDbName);
		$this->assertInstanceOf(Database::class, $mangan->getDbInstance(), 'That first connection is active');


		// Second
		$second = new Mangan('second');
		$this->assertSame($second->dbName, ManganSecondDbName);
		$this->assertInstanceOf(Database::class, $second->getDbInstance(), 'That second connection is active');
	}

}
