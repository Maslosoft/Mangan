<?php namespace Embedded;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\ManganTest\Models\Embedded\WithNullableEmbedded;
use Maslosoft\ManganTest\Models\Plain\SimplePlainEmbedded;
use MongoId;
use UnitTester;

class NullableTest extends Unit
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

    public function testNullableMeta()
	{
		$model = new WithNullableEmbedded();
		$meta = ManganMeta::create($model)->stats;
		$isNullable = $meta->embedded->nullable;
		$this->assertTrue($isNullable, 'Attribute is set');
	}

    // tests
    public function testSavingNullableEmbedded()
    {
		$model = new WithNullableEmbedded();
		$model->_id = new MongoId();
		$model->title = 'stats';

		$em = new EntityManager($model);
		$em->insert();

		$finder = new Finder($model);

		$found = $finder->findByPk($model->_id);

		assert($found instanceof WithNullableEmbedded);

		$this->assertNotNull($found);
		$this->assertSame($found->title, $model->title);
		$this->assertInstanceOf(WithNullableEmbedded::class, $found);
		$this->assertNull($found->stats);
    }
}