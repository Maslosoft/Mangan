<?php

namespace DbRef;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\DbRef\WithPlainDbRef;
use Maslosoft\ManganTest\Models\Plain\SimplePlainDbRef;
use MongoId;
use UnitTester;

class SingleTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testIfPlainObjectWillRefer()
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

		$this->assertNotNull($found);
		$this->assertSame($found->title, $model->title);
		$this->assertTrue($found instanceof WithPlainDbRef);
		$this->assertTrue($found->stats instanceof SimplePlainDbRef);
	}

}
