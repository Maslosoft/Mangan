<?php
namespace Finder;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Interfaces\SortInterface;
use Maslosoft\ManganTest\Models\WithBaseAttributes;
use UnitTester;


class FindTest extends Test
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
		 $model = new WithBaseAttributes();
		 $model->string = 'first';
		 $model->int = 10;

		 $em = new EntityManager($model);
		 $em->insert();

		 $model = new WithBaseAttributes();
		 $model->string = 'second';
		 $model->int = 20;

		 $em = new EntityManager($model);
		 $em->insert();
    }

    protected function _after()
    {
    }

    // tests
    public function testIfCanFindWithSort()
    {
		 $model = new WithBaseAttributes();
		 $finder = new Finder($model);

		 $criteria = new Criteria(null, $model);
		 $criteria->sort('int', SortInterface::SortDesc);
		 $criteria->limit(1);

		 $found = $finder->find($criteria);

		 $sort = $criteria->getSort();

		 codecept_debug($sort);

		 $this->assertSame('second', $found->string, 'That order was applied while searching');
    }

}