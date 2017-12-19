<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sanitizers;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Exceptions\ClassNotFoundException;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\ManganTest\Models\Sanitizers\ModelWithNonExistentSanitizer;
use Maslosoft\ManganTest\Models\Sanitizers\ModelWithNonExistentSanitizer2;
use UnexpectedValueException;

/**
 * NotFoundTest
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NotFoundTest extends Test
{

	public function testIfWillThrowExceptionOnNonExistentSanitizer()
	{
		$model = new ModelWithNonExistentSanitizer;

		try
		{
			ManganMeta::create($model);
			$this->fail('Exception was not thrown');
		}
		catch (UnexpectedValueException $exc)
		{
			codecept_debug($exc->getMessage());
			$this->assertTrue(true, 'That exception was thrown');
		}
		catch (ClassNotFoundException $exc)
		{
			codecept_debug($exc->getMessage());
			$this->assertTrue(true, 'That addendum exception was thrown');
		}
	}

	public function testIfWillThrowExceptionOnClassNotImportedSanitizer()
	{
		$model = new ModelWithNonExistentSanitizer2;
		try
		{
			ManganMeta::create($model);
			$this->fail('Exception was not thrown');
		}
		catch (UnexpectedValueException $exc)
		{
			codecept_debug($exc->getMessage());
			$this->assertTrue(true, 'That exception was thrown');
		}
	}

}
