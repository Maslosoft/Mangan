<?php

namespace Debug;

use Maslosoft\Mangan\Transformers\JsonArray;
use Maslosoft\ManganTest\Models\Debug\WithI18N;
use UnitTester;

class I18NGetterTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillProperlyGetRawI18N()
	{
		$model = new WithI18N();
		$model->setLanguages(['en', 'pl']);
		$model->setLang('en');
		$model->name = 'January';
		$model->setLang('pl');
		$model->name = 'Styczeń';

		$rawI18N = $model->rawI18N;

		$this->assertTrue(array_key_exists('name', $rawI18N));
		$this->assertTrue(array_key_exists('en', $rawI18N['name']));
		$this->assertTrue(array_key_exists('pl', $rawI18N['name']));

		$this->assertSame('January', $rawI18N['name']['en']);
		$this->assertSame('Styczeń', $rawI18N['name']['pl']);
	}

	public function testIfWillProperlyConvertRawI18NToJson()
	{
		$model = new WithI18N();
		$model->setLanguages(['en', 'pl']);
		$model->setLang('en');
		$model->name = 'January';
		$model->setLang('pl');
		$model->name = 'Styczeń';

		$json = JsonArray::fromModel($model);

		$rawI18N = $json['rawI18N'];

		$this->assertTrue(array_key_exists('name', $rawI18N));
		$this->assertTrue(array_key_exists('en', $rawI18N['name']));
		$this->assertTrue(array_key_exists('pl', $rawI18N['name']));

		$this->assertSame('January', $rawI18N['name']['en']);
		$this->assertSame('Styczeń', $rawI18N['name']['pl']);
	}

}
