<?php

namespace Command;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Command;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Model\Command\Roles;
use Maslosoft\Mangan\Model\Command\User;
use MongoDB\Driver\Exception\CommandException;
use UnitTester;

class CommandsTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testCollStats(): void
	{
		$cmd = new Command();
		$info = $cmd->create('test');
		$this->isOk($info);

		$info2 = $cmd->collStats('test');

		$this->isOk($info2);

		$this->assertArrayHasKey('ns', $info2);
	}

	public function testCreateAndDropUser(): void
	{
		$cmd = new Command();
		$user = new User;
		$user->user = 'janko';
		$user->pwd = 'admin123';
		$user->roles = [
			[
				'role' => 'readWrite',
				'db' => Mangan::fly()->dbName
			]
		];

		$this->tryDrop($user);

		$info = $cmd->createUser($user);
		$this->isOk($info);

		$info2 = $cmd->dropUser($user);
		$this->isOk($info2);
	}

	public function testCreateAndDropUserUsingRolesModel(): void
	{
		$cmd = new Command();
		$user = new User;
		$user->user = 'janko';
		$user->pwd = 'admin123';
		$roles = new Roles(Mangan::fly()->dbName, ['readWrite']);
		$user->roles = $roles;
		$rolesArray = $roles->toArray();
		codecept_debug($rolesArray);

		$this->tryDrop($user);

		$this->assertCount(1, $rolesArray);
		$this->assertSame('readWrite', $rolesArray[0]['role']);
		$this->assertSame(Mangan::fly()->dbName, $rolesArray[0]['db']);

		$info = $cmd->createUser($user);
		$this->isOk($info);

		$info2 = $cmd->dropUser($user);
		$this->isOk($info2);
	}

	private function isOk($info): void
	{
		codecept_debug($info);
		$this->assertTrue((bool) $info['ok'], 'That command result is OK');
	}

	/**
	 * Try to drop user in case it was left out after previous tests
	 * @param User $user
	 * @return void
	 */
	private function tryDrop(User $user)
	{

		try
		{
			$cmd = new Command();
			$cmd->dropUser($user);
		}
		catch (CommandException $e)
		{
			codecept_debug("Pre-test drop trying result: " . $e->getMessage());
		}
	}

}
