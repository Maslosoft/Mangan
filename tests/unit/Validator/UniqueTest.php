<?php

namespace Validator;

use Maslosoft\Mangan\Validators\BuiltIn\UniqueValidator;
use Maslosoft\ManganTest\Models\ModelWithI18NFullAr;
use Maslosoft\ManganTest\Models\ModelWithLabel;
use UnitTester;

class UniqueTest extends \Codeception\TestCase\Test
{

	use \Codeception\Specify;

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillValidateWithNewModel()
	{
		$validator = new UniqueValidator();
		$model = new ModelWithLabel();
		$valid = $validator->isValid($model, 'state');
		$this->assertTrue($valid);
	}

	public function testIfWillValidateAfterSave()
	{
		$validator = new UniqueValidator();

		$model = new ModelWithLabel();
		$model->state = 'Arkansas';
		$saved = $model->save();
		$this->assertTrue($saved, 'That model was indeed saved');

		$model = new ModelWithLabel();
		$model->state = 'Alabama';
		$model->save();
		$valid = $validator->isValid($model, 'state');
		$this->assertTrue($valid);

		$model2 = new ModelWithLabel();
		$model2->state = 'Alabama';
		$model2->save();
		$valid = $validator->isValid($model2, 'state');

		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testIfWillValidateSavedModelWithI18N()
	{
		$validator = new UniqueValidator();

		$model1 = new ModelWithI18NFullAr();
		$model1->title = 'foo';
		$model1->save();

		$model2 = new ModelWithI18NFullAr();
		$model2->title = 'bar';
		$model2->save();

		$valid = $validator->isValid($model1, 'title');
		$this->assertTrue($valid);

		$model3 = new ModelWithI18NFullAr();
		$model3->title = 'foo';
		$model3->save();
		$valid = $validator->isValid($model3, 'title');
		$this->assertFalse($valid);

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

}
