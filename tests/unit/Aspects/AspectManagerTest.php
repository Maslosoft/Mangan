<?php
namespace Aspects;

use Maslosoft\Mangan\AspectManager as am;
use Maslosoft\ManganTest\Models\ActiveDocument\DocumentBaseAttributes;

class AspectManagerTest extends \Codeception\Test\Unit
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
    public function testAspects()
    {
    	$model = new DocumentBaseAttributes;

    	am::addAspect($model, 'foo');
		am::addAspect($model, 'bar');

		$this->assertTrue(am::hasAspect($model, 'foo'), 'Has foo aspect');
		$this->assertTrue(am::hasAspect($model, 'bar'), 'Has bar aspect');

		am::removeAspect($model, 'foo');

		$this->assertFalse(am::hasAspect($model, 'foo'), 'Has no foo aspect');
		$this->assertTrue(am::hasAspect($model, 'bar'), 'Has bar aspect');

		am::removeAspect($model, 'bar');

		$this->assertFalse(am::hasAspect($model, 'foo'), 'Has no foo aspect');
		$this->assertFalse(am::hasAspect($model, 'bar'), 'Has no bar aspect');
    }
}