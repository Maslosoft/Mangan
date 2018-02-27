<?php

namespace Finder;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\CompositePrimaryKey;
use Maslosoft\ManganTest\Models\WithBaseAttributes;
use MongoId;
use UnitTester;

class FindAllTest extends Unit
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
	public function testIfWillFindAll()
	{
		$model = new WithBaseAttributes();
		$model->string = 'foo';

		$em = new EntityManager($model);

		$em->insert();

		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);

		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);

		$finder = new Finder($model);
		$all = $finder->findAll();

		$this->assertSame(3, count($all));

		foreach ($all as $found)
		{
			$this->assertInstanceof(WithBaseAttributes::class, $found);
			$this->assertSame('foo', $found->string);
		}
	}

	public function testIfWillFindAllByCriteria()
	{
		$model = new WithBaseAttributes();

		$model->string = 'foo';

		$em = new EntityManager($model);

		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);
		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);
		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);

		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);
		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);
		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);


		$finder = new Finder($model);

		$criteria = new Criteria();

		$criteria->string = 'foo';

		$all = $finder->findAll($criteria);

		$this->assertSame(6, $finder->count());
		$this->assertSame(3, count($all));

		foreach ($all as $found)
		{
			$this->assertInstanceof(WithBaseAttributes::class, $found);
			$this->assertSame('foo', $found->string);
		}
	}

	public function testIfWillFindAllByAttributes()
	{
		$model = new WithBaseAttributes();

		$model->string = 'foo';

		$em = new EntityManager($model);

		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);
		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);
		$model = new WithBaseAttributes();
		$model->string = 'foo';
		$em->insert($model);

		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);
		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);
		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);

		$finder = new Finder($model);

		$all = $finder->findAllByAttributes([
			'string' => 'foo'
		]);

		$this->assertSame(6, $finder->count());
		$this->assertSame(3, count($all));

		foreach ($all as $found)
		{
			$this->assertInstanceof(WithBaseAttributes::class, $found);
			$this->assertSame('foo', $found->string);
		}
	}

	public function testIfWillFindAllBySimplePks()
	{
		$model = new WithBaseAttributes();

		$model->string = 'foo';

		$em = new EntityManager($model);

		$pks[] = $model->_id = new MongoId;
		$em->insert();

		$pks[] = $model->_id = new MongoId;
		$em->insert();

		$pks[] = $model->_id = new MongoId;
		$em->insert();

		$model->_id = new MongoId;
		$em->insert();

		$finder = new Finder($model);

		$all = $finder->findAllByPk($pks);

		$this->assertSame(3, count($all));

		foreach ($all as $found)
		{
			$this->assertInstanceof(WithBaseAttributes::class, $found);
			$this->assertSame('foo', $found->string);
		}
	}

	public function testIfWillFindAllByCompositePks()
	{
		$pks[] = [
			'primaryOne' => new \MongoId,
			'primaryTwo' => 1,
			'primaryThree' => 'one',
			'title' => 'xxx',
		];
		$pks[] = [
			'primaryOne' => new \MongoId,
			'primaryTwo' => 2,
			'primaryThree' => 'two',
			'title' => 'yyy',
		];
		$pks[] = [
			'primaryOne' => new \MongoId,
			'primaryTwo' => 3,
			'primaryThree' => 'three',
			'title' => 'zzz',
		];

		foreach ($pks as $i => $keys)
		{
			$model = new CompositePrimaryKey();
			$em = new EntityManager($model);
			foreach ($keys as $field => $value)
			{
				$model->$field = $value;
			}
			$em->insert();
		}

		$model = new CompositePrimaryKey();
		$em = new EntityManager($model);
		$model->primaryOne = new \MongoId;
		$model->primaryTwo = 12;
		$model->primaryThree = 'ddd';
		$em->insert();

		$finder = new Finder($model);

		$all = $finder->findAllByPk($pks);

		$this->assertSame(3, count($all));

		foreach ($all as $found)
		{
			$this->assertInstanceof(CompositePrimaryKey::class, $found);
			$this->assertTrue(in_array($found->title, ['xxx', 'yyy', 'zzz']));
		}
	}

}
