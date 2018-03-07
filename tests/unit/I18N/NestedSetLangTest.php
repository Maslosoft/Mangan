<?php
namespace I18N;

use Maslosoft\Mangan\Helpers\CompositionIterator;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use Maslosoft\ManganTest\Models\ModelWithI18NAndDbRefs;

class NestedSetLangTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
	/**
	 * @var ModelWithI18NAndDbRefs
	 */
	private $model = null;

	protected function _before()
	{
		$model = new ModelWithI18NAndDbRefs;
		$model->one = new ModelWithI18N;
		$model->many = [
			new ModelWithI18N,
			new ModelWithI18N
		];
		$this->model = $model;
	}


	protected function _after()
    {
    }

    // tests
    public function testIfWillSetLangOnNestedSet()
    {
    	$lang = 'es';
    	$this->model->setLang($lang);

    	foreach(new CompositionIterator($this->model) as $subModel)
		{
			/* @var $subModel ModelWithI18N */
			$this->assertSame($lang, $subModel->getLang());
		}
    }

	public function testKeepSetValue()
	{
		$esTitle = 'Avinguda';
		$enTitle = 'Street';
		$this->setValues('es', $esTitle);
		$this->setValues('en', $enTitle);

		$this->checkValues('es', $esTitle);
		$this->checkValues('en', $enTitle);
	}

	private function setValues($lang, $value)
	{
		$this->model->setLang($lang);

		foreach(new CompositionIterator($this->model) as $subModel)
		{
			codecept_debug("SET:$lang:$value");
			/* @var $subModel ModelWithI18N */
			$subModel->title = $value;
		}
	}

	private function checkValues($lang, $value)
	{
		$this->model->setLang($lang);

		foreach(new CompositionIterator($this->model) as $subModel)
		{
			codecept_debug("CHK:$lang:$value");
			/* @var $subModel ModelWithI18N */
			$this->assertSame($value, $subModel->title);
		}
	}
}