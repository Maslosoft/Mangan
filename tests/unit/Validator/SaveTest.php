<?php

namespace Validator;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\ManganTest\Models\Validator\DocumentWithRequiredValidator;
use Maslosoft\ManganTest\Models\Validator\ModelWithRequiredValidator;
use UnitTester;

class SaveTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testIfWillPreventSaveOfModelIfNotValid()
	{
		$model = new ModelWithRequiredValidator();
		$em = new EntityManager($model);
		$saved = $em->save();

		$this->assertFalse($saved);

		$model->login = 'fooo';

		$saved2 = $em->save();

		$this->assertTrue($saved2);
	}

	public function testIfWillPreventSaveOfDocumentIfNotValid()
	{
		$model = new DocumentWithRequiredValidator();
		$saved = $model->save();

		$this->assertFalse($saved);

		$model->login = 'fooo';

		$saved2 = $model->save();

		$this->assertTrue($saved2);
	}

}
