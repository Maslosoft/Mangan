<?php

namespace Connections;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Mangan;
use MongoDB;
use UnitTester;

class TwoInstancesTest extends Test
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
	public function testIfTwoInstancesOfManganHaveProperConnections()
	{
		// Default
		$mangan = new Mangan();
		$this->assertSame($mangan->dbName, ManganFirstDbName);
		$this->assertInstanceOf(MongoDB::class, $mangan->getDbInstance(), 'That first connection is active');


		// Second
		$second = new Mangan('second');
		$this->assertSame($second->dbName, ManganSecondDbName);
		$this->assertInstanceOf(MongoDB::class, $second->getDbInstance(), 'That second connection is active');
	}

}
