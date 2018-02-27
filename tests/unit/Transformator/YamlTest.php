<?php

namespace Transformator;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Transformers\YamlString;
use Maslosoft\ManganTest\Models\Embedded\WithPlainEmbedded;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use Maslosoft\ManganTest\Models\Plain\SimplePlainEmbedded;
use MongoId;
use UnitTester;

class YamlTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillConvertToYamlAndViceVerse()
	{
		$model = new ModelWithI18N();
		$model->_id = new MongoId();
		$model->title = 'YAML';
		$yaml = YamlString::fromModel($model);
		codecept_debug($yaml);

		$model2 = YamlString::toModel($yaml);
		$this->assertInstanceOf(ModelWithI18N::class, $model2);
		$this->assertSame($model->title, $model2->title);
		$this->assertSame((string) $model->_id, (string) $model2->_id);
	}

	public function testIfWillConvertToYamlAndViceVerseModelWithEmbeddedDocument()
	{
		$model = new WithPlainEmbedded();
		$model->_id = new MongoId();
		$stats = new SimplePlainEmbedded();
		$stats->name = "YAML Stats";
		$model->stats = $stats;
		$model->title = 'YAML';
		$yaml = YamlString::fromModel($model);
		codecept_debug($yaml);

		$model2 = YamlString::toModel($yaml);
		$stats2 = $model2->stats;
		$this->assertInstanceOf(WithPlainEmbedded::class, $model2);
		$this->assertSame($model->title, $model2->title);
		$this->assertSame((string) $model->_id, (string) $model2->_id);

		$this->assertInstanceOf(SimplePlainEmbedded::class, $stats2);
		$this->assertSame($stats->name, $stats2->name);
	}

}
