<?php

namespace DbRef;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\DbRef\WithPlainDbRefArray;
use Maslosoft\ManganTest\Models\DbRef\WithPlainDbRefArrayDifferentTypes;
use Maslosoft\ManganTest\Models\Plain\SimplePlainDbRef;
use Maslosoft\ManganTest\Models\Plain\SimplePlainDbRefSecond;
use MongoId;
use UnitTester;

class ArrayTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testIfWillReferenceArrayOfDocuments()
	{
		$model = new WithPlainDbRefArray();
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
			$referenced = new SimplePlainDbRef();

			foreach ($value as $field => $fieldValue)
			{
				$referenced->$field = $fieldValue;
			}
			$stats[$key] = $referenced;
			$model->stats[$key] = $referenced;
		}
		$em = new EntityManager($model);
		$em->insert();

		$finder = new Finder($model);

		$found = $finder->findByPk($id);

		$this->assertNotNull($found);
		$this->assertInstanceOf(WithPlainDbRefArray::class, $found);
		$this->assertSame(count($stats), count($found->stats));

		foreach ($data as $key => $value)
		{
			$this->assertNotNull($found->stats[$key]);
			$this->assertInstanceOf(SimplePlainDbRef::class, $found->stats[$key]);
			foreach ($value as $field => $fieldValue)
			{
				$this->assertSame($found->stats[$key]->$field, $fieldValue);
			}
		}
	}

	public function testIfWillReferenceArrayOfDifferentTypeDocuments()
	{
		$model = new WithPlainDbRefArrayDifferentTypes();
		$id = new MongoId();
		$model->_id = $id;
		$model->title = 'some title';

		$data = [
			[
				'active' => false,
				'name' => 'stats one',
				'visits' => 100,
				'_type' => SimplePlainDbRef::class
			],
			[
				'active' => true,
				'name' => 'stats two',
				'visits' => 1000,
				'_type' => SimplePlainDbRefSecond::class
			],
			[
				'active' => false,
				'name' => 'stats three',
				'visits' => 10000,
				'_type' => SimplePlainDbRefSecond::class
			],
		];

		$stats = [];
		foreach ($data as $key => $value)
		{
			$referenced = new $value['_type']();

			foreach ($value as $field => $fieldValue)
			{
				if ($field == '_type')
				{
					continue;
				}
				$referenced->$field = $fieldValue;
			}
			$stats[$key] = $referenced;
			$model->stats[$key] = $referenced;
		}
		$em = new EntityManager($model);
		$em->insert();

		$finder = new Finder($model);

		$found = $finder->findByPk($id);

		$this->assertNotNull($found);
		$this->assertTrue($found instanceof WithPlainDbRefArray);
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
