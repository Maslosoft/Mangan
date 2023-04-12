<?php

namespace Command;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Command;
use Maslosoft\ManganTest\Models\Second\ModelWithSecondConnection;
use Maslosoft\ManganTest\Models\SimplePrimaryKey;
use UnitTester;

class BuildinfoTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillGetBuildInformation(): void
	{
		$cmd = new Command();
		$info = $cmd->buildinfo();

		$this->assertArrayHasKey('version', $info);
	}

	public function testIfWillGetBuildInformationWithModelParam(): void
	{
		$cmd = new Command(new ModelWithSecondConnection());
		$info = $cmd->buildinfo();

		$this->assertArrayHasKey('version', $info);

		$cmd = new Command(new SimplePrimaryKey());
		$info = $cmd->buildinfo();

		$this->assertArrayHasKey('version', $info);
	}

}
