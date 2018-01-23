<?php
namespace Issues;

use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\Issues\Model63;
use Maslosoft\ManganTest\Models\Issues\ModelWithId63;
use MongoCursorException;
use MongoId;

class I63Test extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testIfWillAllowUpdatingByCompositePk()
    {
    	$this->withTypeOf(Model63::class);
    }

	public function testIfWillAllowUpdatingByCompositePkWithIdField()
	{
		$this->withTypeOf(ModelWithId63::class);
	}

    private function withTypeOf($class)
	{
		$widgetId = 'myWidget';
		$userId = new MongoId;
		$model = new $class;

		$model->widgetId = $widgetId;
		$model->userId = $userId;

		$em = (new EntityManager($model));
		$saved = $em->upsert();
		$this->assertTrue($saved, 'Model was saved');

		$pk = [
			'widgetId' => $widgetId,
			'userId' => $userId
		];
		$found = (new Finder($model))->findByPk($pk);

		$this->assertNotEmpty($found, 'Model was found');

		$saved2 = (new EntityManager($found))->upsert();
		$this->assertTrue($saved2, 'Model was saved');

		$model2 = new $class;

		$model2->widgetId = $widgetId;
		$model2->userId = $userId;

		// Here issue raised
		$em = (new EntityManager($model2));
		try
		{
			$saved3 = $em->upsert();
		}catch (MongoCursorException $e)
		{
			// Expected behavior
			codecept_debug($e->getMessage());
			$this->assertContains('_id field cannot be changed', $e->getMessage());
			return;
		}
		$this->assertTrue($saved3, 'Model was saved');

		$count = (new Finder($model))->count();

		$this->assertSame(1, $count, 'That only one model was saved');
	}
}