<?php
namespace Criteria;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Criteria;
use UnitTester;


class CriteriaTest extends Unit
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
    public function testIfWillCreateCriteriaForEquallOperator()
    {
		 $criteria = new Criteria();
		 $criteria->title = 'Title';

		 $conditions = $criteria->getConditions();

		 $this->assertSame($conditions['title'], 'Title');
    }

	 public function testIfWillProperlyCreateCriteriaWithAllOperators()
	 {
		 $criteria = new Criteria();

		 foreach(Criteria::$operators as $name => $mongoName)
		 {
			 $criteria->addCond(sprintf('title%s', str_replace('$', '.', $mongoName)), $name, 1);
		 }

		 $conditions = $criteria->getConditions();
	 }
}