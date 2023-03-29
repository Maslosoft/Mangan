<?php

namespace Tree;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\Tree\ModelWithEmbedTree;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class EmbedTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testIfWillProperlyStoreAndLoadTree()
	{
		$model = new ModelWithEmbedTree();
		$id = $model->_id = new MongoId();
		$model->name = 'plants';
		$model->children = [
			new ModelWithEmbedTree('trees', [
				new ModelWithEmbedTree('oaks', [
					new ModelWithEmbedTree('Quercus'),
					new ModelWithEmbedTree('Cerris'),
						]),
				new ModelWithEmbedTree('chestnuts', [
					new ModelWithEmbedTree('Sativa'),
					new ModelWithEmbedTree('Castanea'),
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

	private function checkTree(ModelWithEmbedTree $model)
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
