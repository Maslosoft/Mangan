<?php
namespace Finder;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\WithBaseAttributes;
use UnitTester;


class FindByTest extends Test
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
    public function testIfCanFindByAttributes()
    {
		 $model = new WithBaseAttributes();
		 $model->int = 10;

		 $em = new EntityManager($model);
		 $em->insert();

		 $model = new WithBaseAttributes();
		 $model->int = 20;
		 $em->insert($model);

		 $finder = new Finder($model);
		 $found = $finder->findByAttributes([
			 'int' => 10
		 ]);

		 $this->assertInstanceof(WithBaseAttributes::class, $found);
		 $this->assertSame(10, $found->int);
    }

}