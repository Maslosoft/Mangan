<?php

namespace Decorators;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use Maslosoft\ManganTest\Models\ModelWithI18NAllowAnyAndDefault;
use MongoId;
use UnitTester;

class I18NTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testIfCanGetSetI18NValues()
	{
		$model = new ModelWithI18N();
		$model->setLanguages(['en', 'pl']);
		$model->_id = new MongoId();
		$model->setLang('en');

		$model->title = 'english';
		$model->foo = 'en';
		$model->active = true;

		$model->setLang('pl');
		$model->title = 'polski';
		$model->foo = 'pl';
		$model->active = false;

		$model->setLang('en');
		$this->assertSame('english', $model->title);
		$this->assertSame('en', $model->foo);
		$this->assertSame(true, $model->active);

		$model->setLang('pl');
		$this->assertSame('polski', $model->title);
		$this->assertSame('pl', $model->foo);
		$this->assertSame(false, $model->active);
	}

	public function testIfWillStoreI18NFields()
	{
		$model = new ModelWithI18N();
		$model->setLanguages(['en', 'pl']);
		$model->_id = new MongoId();
		$model->setLang('en');

		$model->title = 'english';
		$model->active = true;

		$em = new EntityManager($model);

		$em->save();

		$finder = new Finder($model);

		$found = $finder->findByPk($model->_id);

		$this->assertNotNull($found);

		$found->setLang('en');

		$this->assertSame($model->title, $found->title);
		$this->assertSame($model->active, $found->active);

		$found->setLang('pl');
		$model->title = 'english';
		$model->active = true;
	}

	public function testIfWillSetValueFromDefaultOrAny()
	{
		$langs = ['en', 'pl', 'ru', 'es'];
		$model = new ModelWithI18NAllowAnyAndDefault;

		$meta = ManganMeta::create($model)->field('title');

		$this->assertTrue($meta->i18n->allowDefault, 'That `allowDefault` is set');
		$this->assertTrue($meta->i18n->allowAny, 'That `allowAny` is set');

		$model->setDefaultLanguage('en');
		$model->setLanguages($langs);
		$model->_id = new MongoId();
		$model->setLang('en');

		$model->title = 'english';
		$model->active = true;

		$em = new EntityManager($model);

		$saved = $em->save();

		$this->assertTrue($saved, 'That save succeed');

		$finderModel = new ModelWithI18NAllowAnyAndDefault;
		$finderModel->setLang('pl');
		$finder = new Finder($finderModel);

		$found = $finder->findByPk($model->_id);

		codecept_debug($found->getLang());

		/* @var $found ModelWithI18NAllowAnyAndDefault */
		$this->assertNotNull($found);

		$found->setDefaultLanguage('en');
		$found->setLanguages($langs);
		$found->setLang('pl');

		$this->assertSame($model->title, $found->title);
		$this->assertSame($model->active, $found->active);
	}
}
