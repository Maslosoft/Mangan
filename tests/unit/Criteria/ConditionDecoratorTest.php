<?php

namespace Criteria;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Criteria\ConditionDecorator;
use Maslosoft\ManganTest\Models\ModelWithArrayField;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use UnitTester;

class ConditionDecoratorTest extends Unit
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

	public function testIfWillDecorateModelWithEmptyArray()
	{
		$model = new ModelWithArrayField();
		$model->setLanguages(['ru', 'en', 'de', 'es']);
		$model->setLang('en');
		$cd = new ConditionDecorator($model);

		$data = $cd->decorate('tags');

		$this->assertSame('tags', key($data));
		$this->assertSame([], $data['tags']);

	}

	public function testIfWillDecorateI18NFields()
	{
		$model = new ModelWithI18N();
		$model->setLanguages(['ru', 'en', 'de', 'es']);
		$model->setLang('en');
		$cd = new ConditionDecorator($model);

		$title = $cd->decorate('title', 'Title');
		$active = $cd->decorate('active', 1);

		$this->assertSame('title.en', key($title));
		$this->assertSame('Title', $title['title.en']);

		$this->assertSame('active.en', key($active));
		$this->assertSame(true, $active['active.en']);
	}

	public function testIfWillDecorateI18NFieldsOnSecondLanguageSet()
	{
		$model = new ModelWithI18N();
		$model->setLanguages(['ru', 'en', 'de', 'es']);
		$model->setLang('en');
		$model->setLang('pl');
		$cd = new ConditionDecorator($model);

		$title = $cd->decorate('title', 'Title');
		$active = $cd->decorate('active', 1);

		$this->assertSame('title.pl', key($title));
		$this->assertSame('Title', $title['title.pl']);

		$this->assertSame('active.pl', key($active));
		$this->assertSame(true, $active['active.pl']);
	}

	public function testIfWillDecorateI18NFieldsOnSecondLanguageSetWithEmptyFieldValue()
	{
		// See https://github.com/Maslosoft/Mangan/issues/82
		$model = new ModelWithI18N();
		$model->setLanguages(['ru', 'en', 'de', 'es']);
		$model->setLang('en');
		$model->setLang('pl');
		$cd = new ConditionDecorator($model);

		$title = $cd->decorate('title');
		$active = $cd->decorate('active');

		$this->assertSame('title.pl', key($title));
		$this->assertSame('active.pl', key($active));
	}
}
