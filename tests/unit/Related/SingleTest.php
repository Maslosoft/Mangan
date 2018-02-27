<?php

namespace Related;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\Related\ModelWithSingleSimpleRelation;
use Maslosoft\ManganTest\Models\Related\RelatedStats;
use MongoId;
use UnitTester;

class SingleTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillSaveAndLoadRelatedModel()
	{
		$model = new ModelWithSingleSimpleRelation();
		$id = $model->_id = new MongoId;
		$model->stats = new RelatedStats();

		$model->stats->name = 'one';

		$em = new EntityManager($model);
		$em->save();

		$finder = new Finder($model);
		$found = $finder->findByPk($id);

		$this->assertInstanceOf(RelatedStats::class, $found->stats);

		$this->assertSame($model->stats->name, $found->stats->name);
	}

}
