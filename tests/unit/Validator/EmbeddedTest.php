<?php

namespace Validator;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Validator;
use Maslosoft\ManganTest\Models\Validator\EmbeddedModelWithValidator;
use Maslosoft\ManganTest\Models\Validator\ModelWithEmbedArrayWithValidator;
use Maslosoft\ManganTest\Models\Validator\ModelWithEmbedWithValidator;
use UnitTester;

class EmbeddedTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillValidateEmbeddedDocument()
	{
		$model = new ModelWithEmbedWithValidator();
		$model->address = new EmbeddedModelWithValidator();

		$validator = new Validator($model);
		$result = $validator->validate();

		codecept_debug($validator->getErrors());

		// Should fail
		$this->assertFalse($result, 'That model is not valid');


		// Should pass
		$model->address->street = 'Wall St.';

		$result2 = $validator->validate();

		$this->assertTrue($result2, 'That model is valid');
	}

	public function testIfWillValidateEmbeddedArrayOfDocuments()
	{
		$model = new ModelWithEmbedArrayWithValidator();
		$model->addresses[] = new EmbeddedModelWithValidator();
		$model->addresses[] = new EmbeddedModelWithValidator();

		$validator = new Validator($model);
		$result = $validator->validate();

		codecept_debug($validator->getErrors());

		// Should fail
		$this->assertFalse($result, 'That model is not valid');

		// Should fail too - one of two models has empty street
		$model->addresses[0]->street = 'Franklin St.';

		$result2 = $validator->validate();
		$this->assertFalse($result2, 'That model is not valid');

		// Should pass
		$model->addresses[1]->street = 'Wall St.';

		$result3 = $validator->validate();

		$this->assertTrue($result3, 'That model is valid');
	}

}
