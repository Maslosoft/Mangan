<?php

namespace Tree;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\Tree\ModelWithDbRefTree;
use MongoId;
use UnitTester;

class DbRefTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillProperlyStoreAndLoadTree()
	{
		$model = new ModelWithDbRefTree();
		$id = $model->_id = new MongoId();
		$model->name = 'plants';
		$model->children = [
			new ModelWithDbRefTree('trees', [
				new ModelWithDbRefTree('oaks', [
					new ModelWithDbRefTree('Quercus'),
					new ModelWithDbRefTree('Cerris'),
						]),
				new ModelWithDbRefTree('chestnuts', [
					new ModelWithDbRefTree('Sativa'),
					new ModelWithDbRefTree('Castanea'),
						]),
					]),
		];
		$this->checkTree($model);
		$em = new EntityManager($model);
		$em->save();

		$found = (new Finder($model))->findByPk($id);
		$this->assertSame((string) $id, (string) $found->_id);

		$this->checkTree($found);
	}

	private function checkTree(ModelWithDbRefTree $model)
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
