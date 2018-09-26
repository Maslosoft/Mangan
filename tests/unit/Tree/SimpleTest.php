<?php

namespace Tree;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Interfaces\SortInterface;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\ManganTest\Models\Tree\ModelWithSimpleTree;
use MongoId;
use UnitTester;

class SimpleTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testReadingRelMeta()
	{
		$model = new ModelWithSimpleTree();

		$relMeta = ManganMeta::create($model)->children->related;

		$this->assertNotEmpty($relMeta);
		codecept_debug($relMeta->sort);
		$this->assertNotEmpty($relMeta->sort);
		$this->assertArrayHasKey('order', $relMeta->sort);
		$this->assertSame(SortInterface::SortAsc, $relMeta->sort['order']);
	}

	// tests
	public function testIfWillProperlyStoreAndLoadTree()
	{
		$model = new ModelWithSimpleTree();
		$id = $model->_id = new MongoId();
		$model->name = 'plants';
		$model->children = [
			new ModelWithSimpleTree('trees', [
				new ModelWithSimpleTree('oaks', [
					new ModelWithSimpleTree('Quercus'),
					new ModelWithSimpleTree('Cerris'),
						]),
				new ModelWithSimpleTree('chestnuts', [
					new ModelWithSimpleTree('Sativa'),
					new ModelWithSimpleTree('Castanea'),
						]),
					]),
		];
		$this->checkTree($model);
		$em = new EntityManager($model);
		$em->save();

		$found = (new Finder($model))->findByPk($id);
		$this->assertSame((string) $id, (string) $found->_id);

		$this->checkTree($found);

		// Find some sub tree
		$attributes = [
			'name' => 'oaks'
		];
		$found2 = (new Finder($model))->findByAttributes($attributes);
		$this->assertNotNull($found2);

		$this->assertSame(2, count($found2->children), 'That has 2 child nodes');
		$this->assertSame('Cerris', $found2->children[1]->name, 'That has 2nd child node of name Cerris');
	}

	private function checkTree(ModelWithSimpleTree $model)
	{
		$this->assertSame('plants', $model->name);
		$this->assertSame('trees', $model->children[0]->name);
		$this->assertSame('oaks', $model->children[0]->children[0]->name);

		$this->assertSame('Quercus', $model->children[0]->children[0]->children[0]->name);
		$this->assertSame('Cerris', $model->children[0]->children[0]->children[1]->name);
		$this->assertSame('chestnuts', $model->children[0]->children[1]->name);
		$this->assertSame('Sativa', $model->children[0]->children[1]->children[0]->name);
		$this->assertSame('Castanea', $model->children[0]->children[1]->children[1]->name);
	}

}
