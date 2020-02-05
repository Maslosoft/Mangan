<?php namespace Criteria;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\ManganTest\Models\Criteria\ModelWithCustomPkAndTypedProperty;
use UnitTester;
use function codecept_debug;

class CustomPkTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testGettingCustomPkWithTypedProperty()
    {
    	$this->markTestSkipped("Test must be ran only on PHP 7.4 or above, there is no constraint now. However issues comes from annotations lib.");
    	$model = new ModelWithCustomPkAndTypedProperty;
    	$keys = PkManager::getPkKeys($model);
    	codecept_debug($keys);
    	$this->assertSame('date', $keys);
    }
}