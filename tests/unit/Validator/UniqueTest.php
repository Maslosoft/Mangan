<?php

namespace Validator;

use Codeception\Specify;
use Codeception\Test\Unit;
use Maslosoft\Mangan\Validators\BuiltIn\UniqueValidator;
use Maslosoft\ManganTest\Models\Debug\ModelWithUniqueValidatorCriteria;
use Maslosoft\ManganTest\Models\ModelWithI18NFullAr;
use Maslosoft\ManganTest\Models\ModelWithLabel;
use UnitTester;

class UniqueTest extends Unit
{

	use Specify;

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillValidateWithNewModel(): void
	{
		$validator = new UniqueValidator();
		$model = new ModelWithLabel();
		$valid = $validator->isValid($model, 'state');
		$this->assertTrue($valid);
	}

	public function testIfWillValidateWithExtraCriteria(): void
	{
		// One
		$model1 = new ModelWithUniqueValidatorCriteria;
		$model1->code = 'test';

		$saved1 = $model1->save();

		$this->assertTrue($saved1, 'That first model was saved');

		// Two
		$model2 = new ModelWithUniqueValidatorCriteria;

		$model2->code = 'test';

		$saved2 = $model2->save();

		$this->assertTrue($saved2, 'That second model was saved');

		// Three with active=true
		$model3 = new ModelWithUniqueValidatorCriteria;

		$model3->code = 'test';
		$model3->active = true;

		$saved3 = $model3->save();

		$this->assertTrue($saved3, 'That third model was saved');

		// Four with active=true
		$model4 = new ModelWithUniqueValidatorCriteria;

		$model4->code = 'test';
		$model4->active = true;

		$saved4 = $model4->save();

		$errors = array_filter($model4->getErrors(), 'count');

		codecept_debug($errors);

		$this->assertFalse($saved4, 'That fourth model was NOT saved');
	}

	public function testIfWillValidateAfterSave(): void
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

	public function testIfWillValidateSavedModelWithI18N(): void
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
