<?php namespace Scopes;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\ScopeManager;
use Maslosoft\ManganTest\Models\Scope\ModelGeneric;
use Maslosoft\ManganTest\Models\Scope\ModelOne;
use Maslosoft\ManganTest\Models\Scope\ModelTwo;
use UnitTester;

class SameClassTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;
    
    // tests

	public function testScopesCriteria()
	{
		$criteria =new Criteria(null, new ModelOne);
		$sm = new ScopeManager(new ModelOne);
		$sm->apply($criteria);

		$conds = $criteria->getConditions();

		$this->assertNotEmpty($conds);
	}

    public function testScopes()
    {
    	$model1 = new ModelOne;
    	$model1->title = 'one';
    	$saved1 = $model1->save();

    	$this->assertTrue($saved1);

		$model2 = new ModelTwo;
		$model2->title = 'two';
		$saved2 = $model2->save();

		$this->assertTrue($saved2);

		$finder = new Finder(new ModelGeneric);
		$models = $finder->findAll();
		$this->assertCount(2, $models, 'Two models were found');

		$finder = new Finder(new ModelOne);
		$models = $finder->findAll();
		$this->assertCount(1, $models, 'One model was found');
		$this->assertInstanceOf(ModelOne::class, $models[0]);
		$this->assertSame('one', $models[0]->title);

		$finder = new Finder(new ModelTwo);
		$models = $finder->findAll();
		$this->assertCount(1, $models, 'One model was found');
		$this->assertInstanceOf(ModelTwo::class, $models[0]);
		$this->assertSame('two', $models[0]->title);
    }
}