<?php

namespace Finder;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\ManganTest\Models\CompositePrimaryKey;
use Maslosoft\ManganTest\Models\Plain\PlainWithBasicAttributes;
use Maslosoft\ManganTest\Models\SimplePrimaryKey;
use MongoId;
use UnitTester;

class PlainTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillFindPlainDocumentBySimplePk()
	{
		$model = new PlainWithBasicAttributes();
		$model->_id = new MongoId();
		$model->int = 232;
		$model->string = 'foo';
		$model->bool = false;
		$model->float = 12.334;
		$model->array = [1, 2, 3, 4, 5, 6];

		$em = new EntityManager($model);
		$em->insert();
		$finder = new Finder($model);
		$found = $finder->findByPk($model->_id);
		$this->assertNotNull($found);
		$this->assertInstanceOf(PlainWithBasicAttributes::class, $found);
		$this->assertSame($model->int, $found->int);
		$this->assertSame($model->string, $found->string);
		$this->assertSame($model->bool, $found->bool);
		$this->assertSame($model->float, $found->float);
		$this->assertSame($model->array, $found->array);

	}

	public function testIfWillFindPlainDocumentWithCustomPk()
	{
		$model = new SimplePrimaryKey();
		$model->primaryKey = new MongoId;
		$model->title = 'bar';

		$em = new EntityManager($model);
		$em->insert();
		$finder = new Finder($model);
		$found = $finder->findByPk($model->primaryKey);

		$this->assertNotNull($found);
		$this->assertInstanceOf(SimplePrimaryKey::class, $found);
		$this->assertSame($model->title, $found->title);
	}

	public function testIfWillFindPlainDocumentWithCompositePk()
	{
		$model = new CompositePrimaryKey();
		$model->title = 'fooo';
		$IdOne = new MongoId();
		$IdTwo = 2;
		$IdThree = (string) new MongoId();
		$model->primaryOne = $IdOne;
		$model->primaryTwo = $IdTwo;
		$model->primaryThree = $IdThree;

		$em = new EntityManager($model);
		$em->insert();
		$finder = new Finder($model);
		$found = $finder->findByPk([
			'primaryOne' => $IdOne,
			'primaryTwo' => $IdTwo,
			'primaryThree' => $IdThree
		]);

		$this->assertNotNull($found);
		$this->assertTrue($found instanceof CompositePrimaryKey);
		$this->assertSame($model->title, $found->title);
	}

	public function testIfWillFindPlainDocumentWithCompositePkWithWrongPkTypes()
	{
//exit;
		$model = new CompositePrimaryKey();
		$model->title = 'fooo';
		$IdOne = new MongoId();
		$IdTwo = 2;
		$IdThree = (string) new MongoId();
		$model->primaryOne = $IdOne;
		$model->primaryTwo = $IdTwo;
		$model->primaryThree = $IdThree;

		$em = new EntityManager($model);
		$em->insert();
		$finder = new Finder($model);

		// Wrong types on purpose
		$found = $finder->findByPk([
			'primaryOne' => (string) $IdOne,
			'primaryTwo' => (string) $IdTwo,
			'primaryThree' => new MongoId($IdThree)
		]);
		$this->assertNotNull($found);
		$this->assertTrue($found instanceof CompositePrimaryKey);
		$this->assertSame($model->title, $found->title);
	}

}
