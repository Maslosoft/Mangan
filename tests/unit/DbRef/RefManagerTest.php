<?php

namespace DbRef;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Helpers\DbRefManager;
use Maslosoft\ManganTest\Models\DbRef\WithPlainDbRef;
use Maslosoft\ManganTest\Models\Plain\SimplePlainDbRef;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class RefManagerTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillProperlyExtractPkCriteria()
	{
		$model = new WithPlainDbRef();
		$model->stats = new SimplePlainDbRef();
		$ref = DbRefManager::extractRef($model, 'stats');

		$this->assertSame($ref->class, SimplePlainDbRef::class);
		$this->assertInstanceOf(MongoId::class, $ref->pk);
	}

}
