<?php

namespace DbRef;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\ManganTest\Models\DbRef\ModelWithNotUpdatableDbRef;
use Maslosoft\ManganTest\Models\DbRef\ModelWithNotUpdatableDbRefShortNotation;
use Maslosoft\ManganTest\Models\DbRef\ModelWithNotUpdatableDbRefShortNotation2;
use Maslosoft\ManganTest\Models\DbRef\ModelWithNotUpdatableDbRefWrongAnnotationValue;
use Maslosoft\ManganTest\Models\DbRef\ModelWithUpdatableDbRef;
use Maslosoft\ManganTest\Models\Plain\SimplePlainDbRef;
use MongoId;
use UnexpectedValueException;
use UnitTester;
use function codecept_debug;

class UpdatableTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function _before()
	{

	}

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
		$this->subTestIfWillNoUpdateRefObject($model);
	}

	public function testIfWillNoUpdateRefObjectWithShortNotation()
	{
		$model = new ModelWithNotUpdatableDbRefShortNotation();
		$this->subTestIfWillNoUpdateRefObject($model);
	}

	public function testIfWillNoUpdateRefObjectWithShortNotation2()
	{
		$model = new ModelWithNotUpdatableDbRefShortNotation2();
		$this->subTestIfWillNoUpdateRefObject($model);
	}

	public function testValidationOfUpdatableParameter()
	{
		$this->markTestSkipped("This test requires clearing annotations cache, as check is made on annotation creation");
		$assertionExceptions = ini_get('assert.exception');

		if(!$assertionExceptions)
		{
			$this->markTestSkipped("PHP option `assert.exception` must be enabled for this test");
		}
		$model = new ModelWithNotUpdatableDbRefWrongAnnotationValue();

		$this->expectException(UnexpectedValueException::class);
		$meta = ManganMeta::create($model);
		codecept_debug($meta->field('stats')->updatable);
		codecept_debug('By default DbRefMeta updates refs, so check if will update');
		$this->subTestIfWillUpdateRefObject($model);
	}


	private function subTestIfWillNoUpdateRefObject($model)
	{
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

	private function subTestIfWillUpdateRefObject($model)
	{
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

	private function subTestIfWillNoUpdateRefArrayObject($model)
	{
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
