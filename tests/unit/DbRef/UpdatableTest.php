<?php

namespace DbRef;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\DbRef\ModelWithNotUpdatableDbRef;
use Maslosoft\ManganTest\Models\DbRef\ModelWithUpdatableDbRef;
use Maslosoft\ManganTest\Models\Plain\SimplePlainDbRef;
use MongoId;
use UnitTester;

class UpdatableTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillUpdateRefObject()
	{
		$model = new ModelWithUpdatableDbRef();
		$stats = new SimplePlainDbRef();
		$stats->name = 'one';
		$statsId = $stats->_id = new MongoId;
		$saved = (new EntityManager($stats))->save();
		$this->assertTrue($saved);

		$model->stats = $stats;

		$statsFinder = new Finder($stats);
		$found = $statsFinder->findByPk($statsId);
		$this->assertSame('one', $found->name);

		$model->stats->name = 'two';
		$saved2 = (new EntityManager($model))->save();
		$this->assertTrue($saved2);

		$found = $statsFinder->findByPk($statsId);
		$this->assertSame('two', $found->name);
	}

	public function testIfWillNoUpdateRefObject()
	{
		$model = new ModelWithNotUpdatableDbRef();
		$stats = new SimplePlainDbRef();
		$stats->name = 'one';
		$statsId = $stats->_id = new MongoId;
		$saved = (new EntityManager($stats))->save();
		$this->assertTrue($saved);

		$model->stats = $stats;

		$statsFinder = new Finder($stats);
		$found = $statsFinder->findByPk($statsId);
		$this->assertSame('one', $found->name);

		$model->stats->name = 'two';
		$saved2 = (new EntityManager($model))->save();
		$this->assertTrue($saved2);

		$found = $statsFinder->findByPk($statsId);
		$this->assertSame('one', $found->name);
	}

}
