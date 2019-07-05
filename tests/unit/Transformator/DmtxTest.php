<?php

namespace Transformator;

use function codecept_debug;
use Codeception\Test\Unit;
use Maslosoft\Cli\Shared\Os;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Transformers\Datamatrix;
use Maslosoft\ManganTest\Models\Embedded\WithPlainEmbedded;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use Maslosoft\ManganTest\Models\Plain\SimplePlainEmbedded;
use MongoId;
use PHPUnit\Framework\SkippedTestError;
use UnitTester;

class DmtxTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests

	public function testIfDatamatrixIsNotAvailable()
	{
		$model = new ModelWithI18N();
		$model->_id = new MongoId;
		$model->title = 'DMTX';
		try
		{
			Datamatrix::fromModel($model);
		}
		catch (ManganException $e)
		{
			codecept_debug($e->getMessage());
			$this->assertContains('php-dmtx', $e->getMessage());
			return;
		}
		if(!ClassChecker::exists(Dmtx\Reader::class))
		{
			$this->fail('Should have thrown exception when dmtx is not available');
		}
	}

	public function testIfWillConvertToDatamatrixAndViceVerse()
	{
		if(!ClassChecker::exists(Dmtx\Reader::class))
		{
			$this->markTestSkipped("PHP DMTX not available");
		}
		$this->assertTrue(Os::commandExists('dmtxwrite'));
		$model = new ModelWithI18N();
		$model->_id = new MongoId;
		$model->title = 'DMTX';
		$dmtx = Datamatrix::fromModel($model);

		if (is_writeable('runtime') || is_writeable('runtime/dmtx1.png'))
		{
			file_put_contents('runtime/dmtx1.png', $dmtx);
		}
		codecept_debug($dmtx);

		$model2 = Datamatrix::toModel($dmtx);
		$this->assertInstanceOf(ModelWithI18N::class, $model2);
		$this->assertSame($model->title, $model2->title);
		$this->assertSame((string) $model->_id, (string) $model2->_id);
	}

	public function testIfWillConvertToDatamatrixAndViceVerseModelWithEmbeddedDocument()
	{
		if(!ClassChecker::exists(Dmtx\Reader::class))
		{
			$this->markTestSkipped("PHP DMTX not available");
		}
		$model = new WithPlainEmbedded();
		$model->_id = new MongoId;
		$stats = new SimplePlainEmbedded();
		$stats->name = "DMTX Stats";
		$model->stats = $stats;
		$model->title = 'DMTX';
		$dmtx = Datamatrix::fromModel($model);

		if (is_writeable('runtime') || is_writeable('runtime/dmtx2.png'))
		{
			file_put_contents('runtime/dmtx2.png', $dmtx);
		}
		codecept_debug($dmtx);

		$model2 = Datamatrix::toModel($dmtx);
		$stats2 = $model2->stats;
		$this->assertInstanceOf(WithPlainEmbedded::class, $model2);
		$this->assertSame($model->title, $model2->title);

		$this->assertInstanceOf(SimplePlainEmbedded::class, $stats2);
		$this->assertSame($stats->name, $stats2->name);
		$this->assertSame((string) $model->_id, (string) $model2->_id);
	}

}
