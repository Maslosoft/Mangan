<?php

namespace Options;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Signals\ConfigInit;

class ConfigInitSignalTest extends Test
{

	public function testIfWillProperlyModifyConfigByReference()
	{
		$config = [
			'san' => [
				'one' => 'abc',
				'two' => 'cde'
			]
		];
		$connId = 'SomeConnectionId';
		$signal = new ConfigInit($config, $connId);

		// Simulate emitting here
		$signal2 = clone $signal;

		// Some configuration changed
		$foreign = [];
		$foreign['san']['three'] = 'def';
		$foreign['san']['one'] = 'xyz';

		$signal2->apply($foreign);

		$this->assertSame($connId, $signal2->getConnectionId(), 'That connection ID is also passed properly');

		$this->assertSame($config['san']['one'], $foreign['san']['one'], 'That original config value was changed');

		$this->assertTrue(array_key_exists('three', $config['san']), 'That original config has new key');
		$this->assertSame($config['san']['three'], $foreign['san']['three'], 'That original config new key has proper value');
	}

}
