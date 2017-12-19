<?php

namespace Transformator;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Transformers\Datamatrix;
use Maslosoft\ManganTest\Models\Embedded\WithPlainEmbedded;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use Maslosoft\ManganTest\Models\Plain\SimplePlainEmbedded;
use MongoId;
use UnitTester;

class DmtxTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillConvertToDatamatrixAndViceVerse()
	{
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
