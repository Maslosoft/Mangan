<?php namespace Issues;

use function codecept_debug;
use Codeception\Test\Unit;
use Maslosoft\Mangan\Exceptions\StructureException;
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

	/**
	 * When trying to save model with field containing document which is not marked with annotations as document, the mongo adapter will fall
	 * into infinite loop or will exhaust memory, whatever will come first.
	 * @link https://github.com/Maslosoft/Mangan/issues/88
	 * @return void
	 */
    public function testAssertingEmbeddedAnnotation(): void
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