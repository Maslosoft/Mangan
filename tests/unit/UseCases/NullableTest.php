<?php namespace UseCases;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\UseCases\ModelWithNullableDate;
use MongoDB\BSON\UTCDateTime as MongoDate;
use UnitTester;

class NullableTest extends Unit
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
    public function testNullableNullDateField()
    {
    	$this->checkNullable(null);
    }

	public function testNullableEmptyStringDateField()
	{
		$this->checkNullable('');
	}

	public function testNullableZeroStringDateField()
	{
		$this->checkNullable('0');
	}

	public function testNullableZeroIntDateField()
	{
		$this->checkNullable('0');
	}

	public function testNullableNotNullValueDateField()
	{
		$this->checkNullableNotNull(new MongoDate);
	}

    private function checkNullable($withValue)
	{
		$model = new ModelWithNullableDate;
		$model->date = $withValue;

		$saved = (new EntityManager($model))->save();
		$this->assertTrue($saved);

		$this->assertNull($model->date);

		$found = (new Finder($model))->find();
		$this->assertNotEmpty($found);
		$this->assertInstanceOf(ModelWithNullableDate::class, $found);
		$this->assertNull($found->date);
	}

	private function checkNullableNotNull($withValue)
	{
		$model = new ModelWithNullableDate;
		$model->date = $withValue;

		$saved = (new EntityManager($model))->save();
		$this->assertTrue($saved);

		$this->assertNotNull($model->date);
		$this->assertInstanceOf(MongoDate::class, $model->date);

		$found = (new Finder($model))->find();
		$this->assertNotEmpty($found);
		$this->assertInstanceOf(ModelWithNullableDate::class, $found);
		$this->assertNotNull($found->date);
		$this->assertInstanceOf(MongoDate::class, $found->date);
	}
}