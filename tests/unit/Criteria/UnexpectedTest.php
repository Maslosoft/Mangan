<?php

namespace Criteria;

use Maslosoft\Mangan\Criteria;
use UnexpectedValueException;

class UnexpectedTest extends \Codeception\Test\Unit
{
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before()
	{
	}

	protected function _after()
	{
	}

	// tests
	public function testUnexpectedConstructorParams()
	{
		$assertionExceptions = ini_get('assert.exception');

		if(!$assertionExceptions)
		{
			$this->markTestSkipped("PHP option `assert.exception` must be enabled for this test");
		}
		
		try
		{
			$criteria = new Criteria('string');
			$this->assertTrue(false, 'Exception was not thrown');
		} catch (UnexpectedValueException $e)
		{
			codecept_debug($e->getMessage());
			$this->assertTrue(true, 'Exception was not thrown');
		}


		try
		{
			$criteria = new Criteria(['some-weird-array' => 'string']);
			$this->assertTrue(false, 'Exception was not thrown');
		} catch (UnexpectedValueException $e)
		{
			codecept_debug($e->getMessage());
			$this->assertTrue(true, 'Exception was not thrown');
		}

		try
		{
			$cfg = [
				'conditions' => [
					'foo' => 'x'
				]
			];
			$criteria = new Criteria($cfg);
			$this->assertTrue(false, 'Exception was not thrown');
		} catch (UnexpectedValueException $e)
		{
			codecept_debug($e->getMessage());
			$this->assertTrue(true, 'Exception was not thrown');
		}

		try
		{
			$cfg = [
				'conditions' => [
					'foo' => ['eqx' => '4']
				]
			];
			$criteria = new Criteria($cfg);
			$this->assertTrue(false, 'Exception was not thrown');
		} catch (UnexpectedValueException $e)
		{
			codecept_debug($e->getMessage());
			$this->assertTrue(true, 'Exception was not thrown');
		}
	}
}