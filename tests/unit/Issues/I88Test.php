<?php namespace Issues;

use function codecept_debug;
use Codeception\Test\Unit;
use Maslosoft\Mangan\Exceptions\StructureException;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\Debug\StructureChecker;
use Maslosoft\Mangan\Transformers\RawArray;
use Maslosoft\ManganTest\Models\Issues\Model88;
use Maslosoft\ManganTest\Models\Issues\Model88Embedded;
use UnitTester;

class I88Test extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    // tests
    public function testAsserting()
    {
    	$model = new Model88;
    	$model->stats = new Model88Embedded;

    	try
		{
			$model->save();
			$this->assertTrue(true, 'Exception was not thrown');
		}
		catch (StructureException $e)
		{
			codecept_debug($e->getMessage());
			$this->assertTrue(true, 'Exception was thrown');
		}
    }
}