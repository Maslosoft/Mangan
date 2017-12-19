<?php

namespace Finder;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Cursor;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\WithBaseAttributes;
use UnitTester;

class FindCursorTest extends Test
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
	public function testIfWillFindAllUsingCursor()
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
		$cursor = $finder->withCursor()->findAll();

		$this->assertSame(3, count($cursor));

		$this->assertInstanceOf(Cursor::class, $cursor);

		foreach ($cursor as $found)
		{
			$this->assertInstanceof(WithBaseAttributes::class, $found);
			$this->assertSame('foo', $found->string);
		}
	}

}
