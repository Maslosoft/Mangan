<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 17.12.17
 * Time: 19:04
 */

namespace Maslosoft\ManganTest\Extensions;

use Codeception\Event\TestEvent;
use Codeception\Extension;
use Exception;
use Maslosoft\Mangan\Helpers\IndexManager;

class IndexMetaCleaner extends Extension
{
	// list events to listen to
	public static $events = [
		'test.before' => 'testBefore',
	];

	public function testBefore(TestEvent $e)
	{
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