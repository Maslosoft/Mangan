<?php

namespace Misc;

use Codeception\Test\Unit;
use Maslosoft\ManganTest\Models\GetSetComponent;
use UnitTester;

class GetSetTest extends Unit
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
