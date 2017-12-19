<?php

namespace Filters;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\ModelWithSecretField;
use UnitTester;

class SecretTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillStoreNonEmptyPassword()
	{
		$model = new ModelWithSecretField();
		$em = new EntityManager($model);
		$finder = new Finder($model);
		$model->password = 'foo';
		$em->update();

		$found = $finder->find();

		$this->assertSame(1, $finder->count(), 'That only one document is in collection');
		$this->assertSame('foo', $found->password, 'That non empty password was saved');

		$found->password = '';
		$em = new EntityManager($found);
		$em->update();

		$found2 = $finder->find();

		$this->assertSame(1, $finder->count(), 'That only one document is in collection');
		$this->assertSame('foo', $found2->password, 'That empty password was NOT saved');
	}

	public function testIfWillGenerateAndStoreNonEmptyHash()
	{
		$hash = sha1('123');
		$model = new ModelWithSecretField();
		$em = new EntityManager($model);
		$finder = new Finder($model);
		$model->hash = '123';
		$em->upsert();

		$found = $finder->find();

		$this->assertSame(1, $finder->count(), 'That only one document is in collection');
		$this->assertSame($hash, $found->hash, 'That non empty hash was saved');

		$found->hash = '';

		$em = new EntityManager($found);
		$em->upsert();

		$found2 = $finder->find();

		$this->assertSame(1, $finder->count(), 'That only one document is in collection');
		$this->assertSame($hash, $found2->hash, 'That empty hash was NOT saved');
	}

	public function testIfWillGenerateAndStoreNonActivationKeyWithArrayCallback()
	{

		$model = new ModelWithSecretField();
		$em = new EntityManager($model);
		$finder = new Finder($model);
		$model->activationKey = true;
		$em->upsert();

		$found = $finder->find();

		$this->assertSame(1, $finder->count(), 'That only one document is in collection');
		$this->assertSame(40, strlen($found->activationKey), 'That non empty activation key was saved as hash');
		$hash1 = $found->activationKey;
		$found->activationKey = '';

		$em = new EntityManager($found);
		$em->upsert();

		$found2 = $finder->find();

		$this->assertSame(1, $finder->count(), 'That only one document is in collection');
		$this->assertSame(40, strlen($found2->activationKey), 'That empty activation key is hash');
		$this->assertSame($hash1, $found2->activationKey, 'That empty activation key was NOT saved');
	}

}
