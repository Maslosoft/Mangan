<?php

namespace Related;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\Related\ModelWithArraySimpleRelation;
use Maslosoft\ManganTest\Models\Related\RelatedStats;
use MongoId;
use UnitTester;

class ManyTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testIfWillSaveAndLoadRelatedArrayOfModels()
	{
		$model = new ModelWithArraySimpleRelation();
		$id = $model->_id = new MongoId;
		$model->stats[] = new RelatedStats();
		$model->stats[] = new RelatedStats();

		$model->stats[0]->name = 'one';
		$model->stats[1]->name = 'one';


		$em = new EntityManager($model);
		$em->save();

		$finder = new Finder($model);
		$found = $finder->findByPk($id);

		$this->assertInstanceOf(RelatedStats::class, $model->stats[0]);
		$this->assertInstanceOf(RelatedStats::class, $model->stats[1]);

		$this->assertSame($model->stats[0]->name, $found->stats[0]->name);
		$this->assertSame($model->stats[1]->name, $found->stats[1]->name);
	}

}
