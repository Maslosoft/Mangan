<?php

namespace Command;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Command;
use Maslosoft\ManganTest\Models\Second\ModelWithSecondConnection;
use Maslosoft\ManganTest\Models\SimplePrimaryKey;
use UnitTester;

class BuildinfoTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillGetBuildInformation()
	{
		$cmd = new Command();
		$info = $cmd->buildinfo();

		$this->assertTrue(array_key_exists('version', $info));
	}

	public function testIfWillGetBuildInformationWithModelParam()
	{
		$cmd = new Command(new ModelWithSecondConnection());
		$info = $cmd->buildinfo();

		$this->assertTrue(array_key_exists('version', $info));

		$cmd = new Command(new SimplePrimaryKey());
		$info = $cmd->buildinfo();

		$this->assertTrue(array_key_exists('version', $info));
	}

}
