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
use Maslosoft\Mangan\Helpers\IndexManager;
use Maslosoft\ManganTest\Models\Indexes\ModelWithCompoundI18NIndex;

class IndexMetaCleaner extends Extension
{
	// list events to listen to
	public static $events = [
		'test.before' => 'testBefore',
	];

	public function testBefore(TestEvent $e)
	{
		$path = IndexManager::fly()->getStoragePath(new ModelWithCompoundI18NIndex, ModelWithCompoundI18NIndex::class);
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