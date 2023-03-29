<?php

namespace Validator;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Validators\BuiltIn\ImmutableValidator;
use Maslosoft\ManganTest\Models\ModelWithImmutableAgainstValidator;
use Maslosoft\ManganTest\Models\ModelWithImmutableValidator;
use Maslosoft\ManganTest\Models\ModelWithLabel;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class ImmutableTest extends Unit
{

	use \Codeception\Specify;

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillValidateWithNewModel()
	{
		$validator = new ImmutableValidator();
		$model = new ModelWithLabel();
		$valid = $validator->isValid($model, 'state');
		$this->assertTrue($valid);
	}

	public function testIfWillValidateAfterSave()
	{
		$id = new MongoId;
		$validator = new ImmutableValidator();

		$model = new ModelWithLabel();
		$model->_id = $id;
		$model->state = 'Arkansas';
		$saved = $model->save();
		$this->assertTrue($saved, 'That model was indeed saved');

		$model1 = new ModelWithLabel();
		$model1->_id = $id;
		$model1->state = 'Arkansas';
		// Don't save here!

		$valid = $validator->isValid($model1, 'state');
		$this->assertTrue($valid, 'That state is the same, so should validate');

		$model2 = new ModelWithLabel();
		$model2->_id = $id;
		$model2->state = 'Alabama';
		// Don't save here!

		$notValid = $validator->isValid($model2, 'state');

		$this->assertFalse($notValid, 'That state change should be forbidden');

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testIfWillValidateWithAnnotation()
	{
		$id = new MongoId;
		$validator = new ImmutableValidator();

		$model = new ModelWithImmutableValidator;
		$model->_id = $id;
		$model->state = 'Arkansas';
		$saved = $model->save();
		$this->assertTrue($saved, 'That model was indeed saved');

		$model->state = 'Arkansas';
		// Don't save here!

		$valid = $model->validate();
		$this->assertTrue($valid, 'That state is the same, so should validate');

		$model->state = 'Alabama';
		// Don't save here!

		$notValid = $model->validate();

		$this->assertFalse($notValid, 'That state change should be forbidden');

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

	public function testIfWillValidateWithAnnotationAndAgainstOption()
	{
		$id = new MongoId;
		$validator = new ImmutableValidator();

		$model = new ModelWithImmutableAgainstValidator;
		$model->_id = $id;
		$model->state = 'Arkansas';
		$saved = $model->save();
		$this->assertTrue($saved, 'That model was indeed saved');

		$model->state = 'Wisconsin';
		// Don't save here!

		$valid = $model->validate();
		$this->assertTrue($valid, 'That state can be changed, because not installed, so should validate');

		$model->installed = true;
		$saved2 = $model->save();

		$this->assertTrue($saved2, 'That model should be saved');

		$model->state = 'Montana';
		// Don't save here!

		$notValid = $model->validate();

		$this->assertFalse($notValid, 'That state change should be forbidden when installed');

		$msg = sprintf('Validator messages: %s', implode(PHP_EOL, $validator->getErrors()));
		codecept_debug($msg);
	}

}
