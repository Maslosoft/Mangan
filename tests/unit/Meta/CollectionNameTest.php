<?php

namespace Meta;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Helpers\CollectionNamer;
use Maslosoft\ManganTest\Models\Meta\WithAnnotatedCollectionName;
use Maslosoft\ManganTest\Models\Meta\WithMethodCollectionName;
use Maslosoft\ManganTest\Models\VoidModel;
use UnitTester;

class CollectionNameTest extends Unit
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
	public function testIfWillProperlyNameCollection()
	{
		$expected = 'Maslosoft.ManganTest.Models.VoidModel';
		$model = new VoidModel();
		$name = CollectionNamer::nameCollection($model);
		$this->assertSame($expected, $name);
	}

	public function testIfWillReadCollectionNameFromAnnotation()
	{
		$expected = WithAnnotatedCollectionName::CollectionName;
		$model = new WithAnnotatedCollectionName();
		$name = CollectionNamer::nameCollection($model);
		$this->assertSame($expected, $name);
	}

	public function testIfWillReadCollectionNameFromMethod()
	{
		$expected = WithMethodCollectionName::CollectionName;
		$model = new WithMethodCollectionName();
		$name = CollectionNamer::nameCollection($model);
		$this->assertSame($expected, $name);
	}

}
