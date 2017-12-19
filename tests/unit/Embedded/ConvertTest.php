<?php

namespace Embedded;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Transformers\JsonArray;
use Maslosoft\ManganTest\Models\Embedded\WithPlainEmbedded;
use Maslosoft\ManganTest\Models\Plain\SimplePlainEmbedded;
use MongoId;
use UnitTester;

class ConvertTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testIfWIllConvertDbRefToJsonArray()
	{
		$model = new WithPlainEmbedded();
		$model->_id = new MongoId();
		$model->title = 'stats';
		$model->stats = new SimplePlainEmbedded();
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
