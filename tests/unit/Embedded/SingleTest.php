<?php

namespace Embedded;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\Embedded\PlainDeepEmbedded;
use Maslosoft\ManganTest\Models\Embedded\WithPlainEmbedded;
use Maslosoft\ManganTest\Models\Plain\SimplePlainEmbedded;
use MongoId;
use UnitTester;

class SingleTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfPlainObjectWillEmbed()
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

		$this->assertNotNull($found);
		$this->assertSame($found->title, $model->title);
		$this->assertInstanceOf(WithPlainEmbedded::class, $found);
		$this->assertInstanceOf(SimplePlainEmbedded::class, $found->stats);
	}

	public function testIfWillDeepEmbed()
	{
		$model = new PlainDeepEmbedded();
		$model->_id = new MongoId();
		$withPlain = new WithPlainEmbedded();
		$stats = new SimplePlainEmbedded();
		$stats->active = false;
		$stats->name = 'buried stats';
		$stats->visits = 100002;
		$withPlain->title = 'first level';
		$withPlain->stats = $stats;
		$model->withPlain = $withPlain;
		$model->title = 'deep blue';

		$em = new EntityManager($model);
		$em->insert();

		$finder = new Finder($model);
		$found = $finder->findByPk($model->_id);

		$this->assertNotNull($found);
		$this->assertTrue($found instanceof PlainDeepEmbedded);
		$this->assertSame($found->title, $model->title);

		$this->assertNotNull($found->withPlain);
		$this->assertTrue($found->withPlain instanceof WithPlainEmbedded);
		$this->assertSame($found->withPlain->title, $model->withPlain->title);

		$this->assertNotNull($found->withPlain->stats);
		$this->assertTrue($found->withPlain->stats instanceof SimplePlainEmbedded);
	}

}
