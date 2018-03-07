<?php
namespace Criteria;

use Maslosoft\Mangan\Criteria;
use Maslosoft\ManganTest\Extensions\CriteriaMergingTester;
use Maslosoft\ManganTest\Models\Criteria\DerivedCriteria;
use Maslosoft\ManganTest\Models\ModelWithI18N;

class MergeTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

	/**
	 * @var ModelWithI18N
	 */
    private $model;

    private $criteriaTester = null;
    
    protected function _before()
    {
		$model = new ModelWithI18N;
		$model->title = 'Test';
		$model->setLanguages(['en', 'es']);
		$model->setLang('en');
		$this->model = $model;
		$this->criteriaTester = new CriteriaMergingTester($this);
    }

    protected function _after()
    {
    }

    // tests
    public function testMergingDecorated()
    {
		$this->subTestMergingDecorated(new Criteria(null, $this->model), new Criteria);
    }

	public function testMergingDecoratedWithDerivedClass()
	{
		$this->subTestMergingDecorated(new Criteria(null, $this->model), new DerivedCriteria);
	}

	public function testMergingDecoratedWithDerivedClassBothWithModel()
	{
		$this->subTestMergingDecorated(new Criteria(null, $this->model), new DerivedCriteria(null, $this->model));
	}

	public function testMergingDecoratedWithBothDerivedClasses()
	{
		$this->subTestMergingDecorated(new DerivedCriteria(null, $this->model), new DerivedCriteria);
	}

	public function testMergingDecoratedWithBothDerivedClassesBothWithModel()
	{
		$this->subTestMergingDecorated(new DerivedCriteria(null, $this->model), new DerivedCriteria(null, $this->model));
	}

	public function subTestMergingDecorated($criteria, $newCriteria)
	{
		$this->criteriaTester->test($criteria, $newCriteria);
	}
}