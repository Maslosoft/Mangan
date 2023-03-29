<?php

namespace Transformator;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Transformers\JsonString;
use Maslosoft\ManganTest\Models\Embedded\WithPlainEmbedded;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use Maslosoft\ManganTest\Models\Plain\SimplePlainEmbedded;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class JsonStringTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillConvertToJsonAndViceVerse()
	{
		$model = new ModelWithI18N();
		$model->_id = new MongoId();
		$model->title = 'JSON';
		$json = JsonString::fromModel($model, [], JSON_PRETTY_PRINT);
		codecept_debug($json);

		$model2 = JsonString::toModel($json);
		$this->assertInstanceOf(ModelWithI18N::class, $model2);
		$this->assertSame($model->title, $model2->title);
		$this->assertSame((string) $model->_id, (string) $model2->_id);
	}

	public function testIfWillConvertToJsonAndViceVerseModelWithEmbeddedDocument()
	{
		$model = new WithPlainEmbedded();
		$model->_id = new MongoId();
		$stats = new SimplePlainEmbedded();
		$stats->name = "JSON+ Stats";
		$model->stats = $stats;
		$model->title = 'JSON';
		$json = JsonString::fromModel($model, [], JSON_PRETTY_PRINT);
		codecept_debug($json);

		$model2 = JsonString::toModel($json);
		$stats2 = $model2->stats;
		$this->assertInstanceOf(WithPlainEmbedded::class, $model2);
		$this->assertSame($model->title, $model2->title);
		$this->assertSame((string) $model->_id, (string) $model2->_id);

		$this->assertInstanceOf(SimplePlainEmbedded::class, $stats2);
		$this->assertSame($stats->name, $stats2->name);
	}

}
