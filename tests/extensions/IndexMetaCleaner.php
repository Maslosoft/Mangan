<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\ManganTest\Extensions;

use Codeception\Event\TestEvent;
use Codeception\Extension;
use Exception;
use Maslosoft\Mangan\Helpers\IndexManager;
use Maslosoft\ManganTest\Extensions\Interfaces\TestDoesNotUseMongoDB;

class IndexMetaCleaner extends Extension
{
	// list events to listen to
	public static $events = [
		'test.before' => 'testBefore',
	];

	public function testBefore(TestEvent $e)
	{
		if($e->getTest() instanceof TestDoesNotUseMongoDB)
		{
			return;
		}
		if(!defined('MANGAN_TEST_ENV'))
		{
			throw new Exception('This extension requires test environment. Constant `MANGAN_TEST_ENV` needs to be true.');
		}
		if(!MANGAN_TEST_ENV)
		{
			throw new Exception('This extension requires test environment. Constant `MANGAN_TEST_ENV` needs to be true.');
		}
		$path = IndexManager::fly()->getStoragePath();
		$dir = realpath(dirname($path));
		IndexManager::$haveDir = false;
		IndexManager::$haveIndex = [];
		// Not exists
		if(empty($dir))
		{
			return;
		}
		foreach(glob("$dir/*") as $filename)
		{
			unlink($filename);
		}
		rmdir($dir);
	}

}