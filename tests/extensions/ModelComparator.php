<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\ManganTest\Extensions;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Transformers\RawArray;
use MongoId;

/**
 * ArrayTester
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelComparator
{

	private $test = null;
	private $comparator = '';

	public function __construct($test, $comparator = RawArray::class)
	{
		$this->test = $test;
		$this->comparator = $comparator;
	}

	public function compare($expectedData, AnnotatedInterface $model, $fields = [])
	{
		$c = $this->comparator;
		$actualData = $c::fromModel($model);
		$this->_compare($expectedData, $actualData, $fields);
	}

	private function _compare($expectedData, $actualData, $fields = [])
	{
		foreach($expectedData as $field => $expected)
		{
			if(count($fields) && !in_array($field, $fields))
			{
				continue;
			}
			$this->test->assertTrue(array_key_exists($field, $actualData), sprintf("Expected field <info>$field</info> in: \n%s", var_export($actualData, true)));
			$actual = $actualData[$field];
			if($this->_special($expectedData, $actualData, $field))
			{
				continue;
			}
			if(is_array($expected))
			{
				$this->_compare($expected, $actual);
				continue;
			}
			$this->test->assertSame($expected, $actual, sprintf("Field: <info>$field</info>\nactualData: %s\n expectedData: %s", var_export($actualData, true), var_export($expectedData, true)));
		}
	}

	private function _special($expectedData, $actualData, $field)
	{
		$expected = $expectedData[$field];
		$actual = $actualData[$field];
		if($expected instanceof MongoId && $actual instanceof MongoId)
		{
			$this->test->assertSame((string)$expected, (string)$actual, sprintf("Field: <info>$field</info>\nactualData: %s\n expectedData: %s", var_export($actualData, true), var_export($expectedData, true)));
			return true;
		}
		return false;
	}
}
