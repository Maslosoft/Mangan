<?php

namespace Misc;

use Codeception\TestCase\Test;
use Maslosoft\ManganTest\Models\GetSetComponent;
use UnitTester;

class GetSetTest extends Test
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
	public function testIfGetSetWorks()
	{
		$model = new GetSetComponent();
		$model->name = GetSetComponent::NameValue;
		$this->assertSame(GetSetComponent::NameValue, $model->name);
	}

}
