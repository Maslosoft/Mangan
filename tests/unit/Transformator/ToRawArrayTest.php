<?php

namespace Transformator;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Transformers\RawArray;
use Maslosoft\ManganTest\Models\Embedded\WithPlainEmbedded;
use Maslosoft\ManganTest\Models\Plain\PlainWithBasicAttributes;
use Maslosoft\ManganTest\Models\Plain\SimplePlainEmbedded;
use UnitTester;

class ToRawArrayTest extends Test
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
	public function testIfWillConvertToArraySimplePlainModel()
	{
		$model = new PlainWithBasicAttributes();

		$data = [
			'int' => 12345,
			'string' => 'foo',
			'bool' => false,
			'float' => 110.23,
			'array' => ['new', 'array'],
			'null' => null,
		];
		foreach ($data as $field => $value)
		{
			$model->$field = $value;
		}

		$raw = RawArray::fromModel($model);

		$this->assertTrue(array_key_exists('_class', $raw));
		$this->assertSame($raw['_class'], PlainWithBasicAttributes::class);
		foreach ($data as $field => $value)
		{
			$this->assertSame($model->$field, $raw[$field]);
		}
	}

	public function testIfWillConvertToArrayWithEmbeddedDocuments()
	{
		$model = new WithPlainEmbedded();
		$model->title = 'stats';
		$model->stats = new SimplePlainEmbedded();
		$model->stats->active = true;
		$model->stats->name = 'foo';
		$model->stats->visits = 1233;

		$raw = RawArray::fromModel($model);

		$this->assertSame($raw['title'], $model->title);
		
		foreach(['active', 'name', 'visits'] as $name)
		{
			$this->assertSame($model->stats->$name, $raw['stats'][$name]);
		}
	}

}
