<?php

namespace Decorators;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Transformers\JsonArray;
use Maslosoft\Mangan\Transformers\RawArray;
use Maslosoft\ManganTest\Models\ModelWithAlias;
use Maslosoft\ManganTest\Models\ModelWithAliasDefault;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class AliasTest extends Unit
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
	public function testIfWillPopulateArrayWithAliasedField()
	{
		$model = new ModelWithAlias();

		$model->id = new MongoId;

		$data = RawArray::fromModel($model);

		$this->assertSame($data['id'], $data['_id']);

		$model = new ModelWithAlias();

		$model->_id = new MongoId;

		$data = RawArray::fromModel($model);

		$this->assertSame($data['id'], $data['_id']);
	}

	public function testIfWillPopulateModelWithRawData()
	{
		// This model uses custom alias setup
		$src = new ModelWithAlias();
		$data = [
			'_id' => new MongoId('5612522b66a195f1328b4575'),
			'id' => new MongoId('5612522b66a195f1328b4575')
		];
		$model = RawArray::toModel($data, $src);

		$this->assertSame((string) $model->id, (string) $model->_id);

		$this->assertSame('5612522b66a195f1328b4575', (string) $model->_id);
		$this->assertSame('5612522b66a195f1328b4575', (string) $model->id);
	}

	public function testIfWillPopulateDefaultModelWithRawData()
	{
		// This model uses default alias setup from Document class
		$src = new ModelWithAliasDefault();
		$data = [
			'_id' => new MongoId('5612522b66a195f1328b4575'),
			'id' => new MongoId('5612522b66a195f1328b4575')
		];
		$model = RawArray::toModel($data, $src);

		$this->assertSame((string) $model->id, (string) $model->_id);

		$this->assertSame('5612522b66a195f1328b4575', (string) $model->_id);
		$this->assertSame('5612522b66a195f1328b4575', (string) $model->id);
	}

	public function testIfWillPopulateJsonArrayWithModelDataWithAutoIdFromSanitizers()
	{
		// This model uses default alias setup from Document class
		// Do not set ID explicitly. Should auto set, and make alias too.
		$src = new ModelWithAliasDefault();

		$data = JsonArray::fromModel($src);

		$this->assertSame((string) $data['id'], (string) $data['_id']);

		$this->assertSame((string) $src->_id, (string) $data['_id']);
		$this->assertSame((string) $src->id, (string) $data['id']);
	}

	public function testIfWillCreateModelWithAliasedField()
	{
		$data = [
			'_class' => ModelWithAlias::class,
			'id' => new MongoId()
		];

		$encoded = json_encode($data);
		$decoded = json_decode($encoded);
		$model = JsonArray::toModel($decoded);

		$this->assertSame((string) $model->_id, (string) $model->id);

		$data = [
			'_class' => ModelWithAlias::class,
			'_id' => new MongoId()
		];

		$encoded = json_encode($data);
		$decoded = json_decode($encoded);
		$model = JsonArray::toModel($decoded);

		$this->assertSame((string) $model->_id, (string) $model->id);
	}

}
