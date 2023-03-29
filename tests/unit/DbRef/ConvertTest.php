<?php

namespace DbRef;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Transformers\JsonArray;
use Maslosoft\ManganTest\Models\DbRef\WithPlainDbRef;
use Maslosoft\ManganTest\Models\Plain\SimplePlainDbRef;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class ConvertTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWIllConvertDbRefToJsonArray()
	{
		$model = new WithPlainDbRef();
		$model->_id = new MongoId();
		$model->title = 'stats';
		$model->stats = new SimplePlainDbRef();
		$model->stats->active = true;
		$model->stats->name = 'www';
		$model->stats->visits = 10000;

		$em = new EntityManager($model);
		$em->insert();

		$finder = new Finder($model);

		$found = $finder->findByPk($model->_id);
		$json = JsonArray::fromModel($found);
		$this->assertSame($found->title, $json['title']);
		$this->assertSame($found->stats->active, $json['stats']['active']);
		$this->assertSame($found->stats->name, $json['stats']['name']);
	}

}
