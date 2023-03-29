<?php
namespace Sanitizers;


use Maslosoft\Mangan\EntityManager;
use Maslosoft\ManganTest\Models\Sanitizers\ModelWithNullableBoolean;
use MongoDB\BSON\ObjectId as MongoId;

class NullableBooleanTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;


    // tests
	public function testIfWillAllowNullForBooleanValue()
	{
		$model = new ModelWithNullableBoolean();
		$em = new EntityManager($model);
		$saved = $em->save();

		$this->assertTrue($saved);
		$this->assertInstanceOf(MongoId::class, $model->_id);
		$this->assertNull($model->value);

		$model->value = true;
		$em->save();
		$this->assertInstanceOf(MongoId::class, $model->_id);
		$this->assertTrue($model->value);
	}
}