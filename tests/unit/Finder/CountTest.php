<?php

namespace Finder;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\WithBaseAttributes;
use UnitTester;

class CountTest extends Unit
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
	public function testIfWillCount()
	{
		$model = new WithBaseAttributes();

		$em = new EntityManager($model);

		$em->insert(new WithBaseAttributes());
		$em->insert(new WithBaseAttributes());
		$em->insert(new WithBaseAttributes());

		$finder = new Finder($model);

		$count = $finder->count();

		$this->assertSame(3, $count);
	}

	public function testIfWillCountByCriteria()
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

		// Some other models
		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);

		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);

		$finder = new Finder($model);

		$count = $finder->count();

		$this->assertSame(5, $count);

		$criteria = new Criteria();

		$criteria->addCond('string', '==', 'foo');

		$criteriaCount = $finder->count($criteria);

		$this->assertSame(3, $criteriaCount);
	}

	public function testIfWillCountByAttributes()
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

		// Some other models

		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);

		$model = new WithBaseAttributes();
		$model->string = 'blah';
		$em->insert($model);

		$finder = new Finder($model);

		$count = $finder->count();

		$this->assertSame(5, $count);

		$attributesCount = $finder->countByAttributes([
			'string' => 'foo'
		]);

		$this->assertSame(3, $attributesCount);
	}

}
