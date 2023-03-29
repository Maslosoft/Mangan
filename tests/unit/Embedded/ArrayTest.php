<?php

namespace Embedded;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\Embedded\WithPlainEmbeddedArray;
use Maslosoft\ManganTest\Models\Embedded\WithPlainEmbeddedArrayDifferentTypes;
use Maslosoft\ManganTest\Models\Plain\SimplePlainEmbedded;
use Maslosoft\ManganTest\Models\Plain\SimplePlainEmbeddedSecond;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class ArrayTest extends Unit
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
	public function testIfWillEmbedArrayOfDocuments()
	{
		$model = new WithPlainEmbeddedArray();
		$id = new MongoId();
		$model->_id = $id;
		$model->title = 'some title';

		$data = [
			[
				'active' => false,
				'name' => 'stats one',
				'visits' => 100,
			],
			[
				'active' => true,
				'name' => 'stats two',
				'visits' => 1000,
			],
			[
				'active' => false,
				'name' => 'stats three',
				'visits' => 10000,
			],
		];

		$stats = [];
		foreach ($data as $key => $value)
		{
			$embedded = new SimplePlainEmbedded();

			foreach ($value as $field => $fieldValue)
			{
				$embedded->$field = $fieldValue;
			}
			$stats[$key] = $embedded;
			$model->stats[$key] = $embedded;
		}
		$em = new EntityManager($model);
		$em->insert();

		$finder = new Finder($model);

		$found = $finder->findByPk($id);

		$this->assertNotNull($found);
		$this->assertTrue($found instanceof WithPlainEmbeddedArray);
		$this->assertSame(count($stats), count($found->stats));

		foreach ($data as $key => $value)
		{
			$this->assertNotNull($found->stats[$key]);
			$this->assertTrue($found->stats[$key] instanceof SimplePlainEmbedded);
			foreach ($value as $field => $fieldValue)
			{
				$this->assertSame($found->stats[$key]->$field, $fieldValue);
			}
		}
	}

	public function testIfWillEmbedArrayOfDifferentTypeDocuments()
	{
		$model = new WithPlainEmbeddedArrayDifferentTypes();
		$id = new MongoId();
		$model->_id = $id;
		$model->title = 'some title';

		$data = [
			[
				'active' => false,
				'name' => 'stats one',
				'visits' => 100,
				'_type' => SimplePlainEmbedded::class
			],
			[
				'active' => true,
				'name' => 'stats two',
				'visits' => 1000,
				'_type' => SimplePlainEmbeddedSecond::class
			],
			[
				'active' => false,
				'name' => 'stats three',
				'visits' => 10000,
				'_type' => SimplePlainEmbeddedSecond::class
			],
		];

		$stats = [];
		foreach ($data as $key => $value)
		{
			$embedded = new $value['_type']();

			foreach ($value as $field => $fieldValue)
			{
				if ($field == '_type')
				{
					continue;
				}
				$embedded->$field = $fieldValue;
			}
			$stats[$key] = $embedded;
			$model->stats[$key] = $embedded;
		}
		$em = new EntityManager($model);
		$em->insert();

		$finder = new Finder($model);

		$found = $finder->findByPk($id);

		$this->assertNotNull($found);
		$this->assertTrue($found instanceof WithPlainEmbeddedArray);
		$this->assertSame(count($stats), count($found->stats));

		foreach ($data as $key => $value)
		{
			$this->assertNotNull($found->stats[$key]);
//			$this->write(sprintf('Should be of type: %s', $value['_type']));
			$this->assertSame(get_class($found->stats[$key]), $value['_type']);
			$this->assertTrue($found->stats[$key] instanceof $value['_type']);
			foreach ($value as $field => $fieldValue)
			{
				if ($field == '_type')
				{
					continue;
				}
				$this->assertSame($found->stats[$key]->$field, $fieldValue);
			}
		}
	}

}
