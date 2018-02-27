<?php

namespace Validator;

use Codeception\Test\Unit;
use Maslosoft\ManganTest\Models\Validator\UserEmail;
use Maslosoft\ManganTest\Models\Validator\UserWithEmail;
use Maslosoft\ManganTest\Models\Validator\UserWithEmails;
use UnitTester;

class NestedTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillValidateManyNestedDocumentsAndShowErrorMessage()
	{
		$user = new UserWithEmails();
		$user->emails[] = new UserEmail();

		$valid = $user->validate();

		$this->assertFalse($valid, 'That model is not valid, as sub model has empty e-mails');

		$allErrors = $user->getErrors();
		$this->assertArrayHasKey('emails', $allErrors);
		$this->assertArrayHasKey(0, $allErrors['emails']);
		$errors = array_filter($allErrors['emails'][0], 'count');

		codecept_debug("Need to pass error message to parent object");

		codecept_debug($errors);

		$this->assertNotEmpty($errors, 'That error message from sub object is included');
	}

	// tests
	public function testIfWillValidateOneNestedDocumentsAndShowErrorMessage()
	{
		$user = new UserWithEmail();
		$user->emails = new UserEmail();

		$valid = $user->validate();

		$this->assertFalse($valid, 'That model is not valid, as sub model has empty e-mails');

		$allErrors = $user->getErrors();
		$this->assertArrayHasKey('emails', $allErrors);
		$errors = array_filter($allErrors['emails'], 'count');

		codecept_debug("Need to pass error message to parent object");

		codecept_debug($errors);

		$this->assertNotEmpty($errors, 'That error message from sub object is included');
	}

}
