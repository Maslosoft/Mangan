<?php

namespace Transformator;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Transformers\DocumentArray;
use Maslosoft\ManganTest\Models\Embedded\PlainDeepEmbedded;
use Maslosoft\ManganTest\Models\Embedded\WithPlainEmbedded;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use Maslosoft\ManganTest\Models\ModelWithI18NAndIgnoredArrayField;
use Maslosoft\ManganTest\Models\Plain\PlainWithBasicAttributes;
use Maslosoft\ManganTest\Models\Plain\SimplePlainEmbedded;
use MongoId;
use UnitTester;

class DocumentArrayTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function _before()
	{

	}

	protected function _after()
	{

	}

	// tests
	public function testIfWillPopulateSimplePlainModel()
	{
		$data = [
			'_class' => PlainWithBasicAttributes::class,
			'int' => 12345,
			'string' => 'foo',
			'bool' => false,
			'float' => 110.23,
			'array' => ['new', 'array'],
			'null' => null,
		];

		$model = DocumentArray::toModel($data);
		$this->assertTrue($model instanceof PlainWithBasicAttributes);
		unset($data['_class']);
		foreach ($data as $field => $value)
		{
			$this->assertSame($value, $model->$field);
		}
	}

	public function testIfWillPopulateModelWithI18N()
	{
		$data = [
			'_class' => ModelWithI18N::class,
			'active' => true,
			'foo' => 'foo bar vaz',
			'title' => 'My Title'
		];

		$model = DocumentArray::toModel($data);
		$this->assertTrue($model instanceof ModelWithI18N);
		unset($data['_class']);
		foreach ($data as $field => $value)
		{
			$this->assertSame($value, $model->$field);
		}
	}

	public function testIfWillPopulateModelWithI18NAndIgnoredArrayField()
	{
		$data = [
			'_class' => ModelWithI18NAndIgnoredArrayField::class,
			'active' => true,
			'foo' => 'foo bar vaz',
			'title' => 'My Title',
			'notArray' => false
		];

		$model = DocumentArray::toModel($data);
		$this->assertTrue($model instanceof ModelWithI18NAndIgnoredArrayField);
		unset($data['_class']);

		foreach ($data as $field => $value)
		{
			if ($field == 'notArray')
			{
				$this->assertTrue($model->$field);
				continue;
			}
			$this->assertSame($value, $model->$field);
		}
	}

	public function testIfWillPopulateModelWithEmbeddedDocument()
	{
		$data = [
			'title' => 'deep blue',
			'withPlain' =>
			[
				'stats' =>
				[
					'name' => 'buried stats',
					'active' => false,
					'visits' => 100002,
					'_class' => SimplePlainEmbedded::class,
				],
				'title' => 'first level',
				'_class' => WithPlainEmbedded::class,
			],
			'withPlainArray' =>
			[
			],
			'_class' => PlainDeepEmbedded::class,
		];

		$found = DocumentArray::toModel($data);

		$this->assertNotNull($found);
		$this->assertTrue($found instanceof PlainDeepEmbedded);
		$this->assertSame($found->title, $data['title']);

		$this->assertNotNull($found->withPlain);
		$this->assertTrue($found->withPlain instanceof WithPlainEmbedded);
		$this->assertSame($found->withPlain->title, $data['withPlain']['title']);

		$this->assertNotNull($found->withPlain->stats);
		$this->assertTrue($found->withPlain->stats instanceof SimplePlainEmbedded);
	}

	public function testIfWillConvertToArrayModelWithEmbeddedDocument()
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

		$data = [
			'title' => 'deep blue',
			'withPlain' =>
			[
				'stats' =>
				[
					'name' => 'buried stats',
					'active' => false,
					'visits' => 100002,
					'_class' => SimplePlainEmbedded::class,
				],
				'title' => 'first level',
				'_class' => WithPlainEmbedded::class,
			],
			'withPlainArray' =>
			[
			],
			'_class' => PlainDeepEmbedded::class,
		];

		$data = DocumentArray::fromModel($model);

		$this->assertSame($model->title, $data['title']);
		$this->assertSame($model->withPlain->title, $data['withPlain']['title']);
		$this->assertSame(get_class($model->withPlain), $data['withPlain']['_class']);
		$this->assertSame($model->withPlain->stats->name, $data['withPlain']['stats']['name']);

	}

}
