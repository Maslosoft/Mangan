<?php

namespace Transformator;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Transformers\JsonArray;
use Maslosoft\ManganTest\Models\ActiveDocument\DocumentBaseAttributes;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use Maslosoft\ManganTest\Models\ModelWithI18NAndIgnoredJsonField;
use Maslosoft\ManganTest\Models\Plain\PlainWithBasicAttributes;
use MongoId;
use UnitTester;

class JsonArrayTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

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

		$encoded = json_encode($data);
		$decoded = json_decode($encoded);
		$model = JsonArray::toModel($decoded);
		$this->assertInstanceOf(PlainWithBasicAttributes::class, $model);
		unset($data['_class']);
		foreach ($data as $field => $value)
		{
			$this->assertSame($value, $model->$field);
		}
	}

	public function testIfWillConvertIdToString()
	{
		$model = new ModelWithI18N();
		$model->_id = new MongoId;

		$json = JsonArray::fromModel($model);

		$this->assertIsString($json['_id']);
	}

	public function testIfWillPopulateModelWithI18N()
	{
		$data = [
			'_class' => ModelWithI18N::class,
			'active' => true,
			'foo' => 'foo bar vaz',
			'title' => 'My Title'
		];

		$encoded = json_encode($data);
		$decoded = json_decode($encoded);
		$model = JsonArray::toModel($decoded);
		$this->assertInstanceOf(ModelWithI18N::class, $model);
		unset($data['_class']);
		foreach ($data as $field => $value)
		{
			$this->assertSame($value, $model->$field);
		}
	}

	public function testIfWillPopulateModelWithI18NAndIgnoredJsonField()
	{
		$data = [
			'_class' => ModelWithI18NAndIgnoredJsonField::class,
			'active' => true,
			'foo' => 'foo bar vaz',
			'title' => 'My Title',
			'notJson' => false
		];

		$encoded = json_encode($data);
		$decoded = json_decode($encoded);
		$model = JsonArray::toModel($decoded);
		$this->assertInstanceOf(ModelWithI18NAndIgnoredJsonField::class, $model);
		unset($data['_class']);
		foreach ($data as $field => $value)
		{
			if ($field == 'notJson')
			{
				$this->assertTrue($model->$field);
				continue;
			}
			$this->assertSame($value, $model->$field);
		}
	}

	public function testIfWillPopulateDocumentInstance()
	{
		$data = [
			'_class' => DocumentBaseAttributes::class,
			'_id' => new MongoId(),
			'string' => 'Some new string value',
			'int' => 15100900,
			'float' => 455.34
		];

		$encoded = json_encode($data);
		$decoded = json_decode($encoded);
		$model = JsonArray::toModel($decoded);
		$this->assertInstanceOf(DocumentBaseAttributes::class, $model);
		unset($data['_class']);
		foreach ($data as $field => $value)
		{
			if ($field == '_id')
			{
				$this->assertSame((string) $value, (string) $model->_id);
				continue;
			}
			$this->assertSame($value, $model->$field);
		}
	}

	public function testIfWillConvertDocumentInstanceToJson()
	{
		$src = [
			'string' => 'Some new string value',
			'int' => 15100900,
			'float' => 455.34
		];

		$model = new DocumentBaseAttributes;
		foreach ($src as $field => $value)
		{
			$model->$field = $value;
		}

		$data = JsonArray::fromModel($model);
		foreach ($data as $field => $value)
		{
			if ($field === '_id' || $field === 'id')
			{
				$this->assertSame((string) $value, (string) $model->$field, "Tested field name is `$field`");
			}
			else
			{
				$this->assertSame($value, $model->$field, "Tested field name is `$field`");
			}
		}

		$this->assertTrue(isset($data['_class']));
		$this->assertSame(DocumentBaseAttributes::class, $data['_class']);

		// `meta` property should be ignored
		$this->assertFalse(isset($data['meta']));
	}

}
