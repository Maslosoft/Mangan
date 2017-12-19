<?php
namespace Document;

use Codeception\TestCase\Test;
use Maslosoft\ManganTest\Models\ActiveDocument\DocumentBaseAttributes;
use MongoId;
use UnitTester;


class ActiveDocumentTest extends Test
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

	public function testIfCanReadAttributes()
	{
		$m = new DocumentBaseAttributes();
		$this->assertSame($m->int, 23);
		$this->assertSame($m->string, 'test');
		$this->assertSame($m->bool, true);
		$this->assertSame($m->float, 0.23);
		$this->assertSame($m->array, []);
		$this->assertSame($m->null, null);
	}

	public function testIfWillFindActiveDocumentByPk()
	{
		$model = new DocumentBaseAttributes();
		$model->_id = new MongoId();
		$model->string = 'foo';
		$model->insert();

		$found = $model->findByPk($model->_id);
		$this->assertNotNull($found);
		$this->assertTrue($found instanceof DocumentBaseAttributes);
		$this->assertSame($model->string, $found->string);
	}
}