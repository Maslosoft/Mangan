<?php

namespace Sanitizers;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Helpers\Sanitizer\Sanitizer;
use Maslosoft\ManganTest\Models\WithSanitizedArrayValues;
use UnitTester;

class ArrayTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testWillSanitizeArraysOfAttributes()
	{
		$model = new WithSanitizedArrayValues();
		$model->goals = [
			'1',
			'a2',
			'2a'
		];
		$model->shots = [
			1,
			0.2,
			'0',
			'yes'
		];
		$model->title = [
			1,
			true,
			1.2
		];
		$sanitizer = new Sanitizer($model);

		$this->assertSame([1, 0, 2], $sanitizer->read('goals', $model->goals));
		$this->assertSame([true, true, false, true], $sanitizer->read('shots', $model->shots));
		$this->assertSame(['1', '1', '1.2'], $sanitizer->read('title', $model->title));

		$this->assertSame([1, 0, 2], $sanitizer->write('goals', $model->goals));
		$this->assertSame([true, true, false, true], $sanitizer->write('shots', $model->shots));
		$this->assertSame(['1', '1', '1.2'], $sanitizer->write('title', $model->title));
	}

}
