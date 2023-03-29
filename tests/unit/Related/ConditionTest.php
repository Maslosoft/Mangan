<?php

namespace Related;

use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\Related\ModelWithConditionalRelation;
use Maslosoft\ManganTest\Models\Related\RelatedStats;
use Maslosoft\ManganTest\Models\Related\RelatedType;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class ConditionTest extends \Codeception\Test\Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function _before()
	{
		// Add extra models to ensure that *proper* one gets loaded
		$stats = new RelatedType();

		$stats->name = 'first';
		$stats->type = RelatedType::TypeImage;
		$saved = (new EntityManager($stats))->save();
		$this->assertTrue($saved);

		$stats = new RelatedType();

		$stats->name = 'second';
		$stats->type = RelatedType::TypeImage;
		$saved = (new EntityManager($stats))->save();
		$this->assertTrue($saved);
	}

	protected function _after()
	{

	}

	public function testIfWillSaveAndLoadRelatedModel()
	{
		$model = new ModelWithConditionalRelation();
		$id = $model->_id = new MongoId;
		$model->stats = new RelatedType();

		$model->stats->name = 'one';
		$model->stats->type = RelatedType::TypeText;

		$em = new EntityManager($model);
		$em->save();

		$finder = new Finder($model);
		$found = $finder->findByPk($id);

		$this->assertInstanceOf(RelatedType::class, $found->stats);

		$this->assertSame($model->stats->name, $found->stats->name);
	}

	public function testIfWillLoadPreviouslySavedRelatedModel()
	{
		$stats = new RelatedType();

		$stats->name = 'one';
		$stats->type = RelatedType::TypeText;
		$saved = (new EntityManager($stats))->save();
		$this->assertTrue($saved);

		$model = new ModelWithConditionalRelation();
		$id = $model->_id = new MongoId;

		$em = new EntityManager($model);
		$em->save();

		$statsFinder = new Finder($stats);
		$count = $statsFinder->count(['conditions' => ['type' => ['==' => RelatedType::TypeText]]]);

		$this->assertSame(1, $count, 'That only one of type was saved');

		$finder = new Finder($model);
		$found = $finder->findByPk($id);

		$this->assertInstanceOf(RelatedType::class, $found->stats);

		$this->assertSame($stats->name, $found->stats->name);
	}

}
