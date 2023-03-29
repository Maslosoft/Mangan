<?php
namespace Document;

use Codeception\Test\Unit;
use Maslosoft\ManganTest\Models\BaseAttributesNoAnnotations;
use MongoDB\BSON\ObjectId as MongoId;

/**
 * Document_BaseAttributesNoAnnotationsTest
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class BaseAttributesNoAnnotationsTest extends Unit
{

	public function testIfCanReadAttributes()
	{
		$m = new BaseAttributesNoAnnotations();
		$this->assertSame($m->int, 23);
		$this->assertSame($m->string, 'test');
		$this->assertSame($m->bool, true);
		$this->assertSame($m->float, 0.23);
		$this->assertSame($m->array, []);
		$this->assertSame($m->null, null);
	}

	public function testCanWriteAttributes()
	{
		$id = new MongoId;
		$model = new BaseAttributesNoAnnotations();
		$model->id = $id;
		$model->int = 32;
		$model->string = 'tset';
		$model->bool = false;
		$model->float = 23.23;
		$model->array = ['foo' => 'bar'];
		$model->null = 'not-null';

//		$em = new EntityManager($model);
//		$em->save();
//
//		$finder = new Finder($em);
//
//		$criteria = new Criteria();
//		$criteria->id = $id;
//		$finder->find($criteria);

//		$this->assertSame($m->int, 32);
//		$this->assertSame($m->string, 'tset');
//		$this->assertSame($m->bool, false);
//		$this->assertSame($m->float, 23.23);
//		$this->assertSame($m->array, ['foo' => 'bar']);
//		$this->assertSame($m->null, 'not-null');
	}

	public function testWillSanitizeAttributes()
	{
		$m = new BaseAttributesNoAnnotations();
		$m->int = '32';
		$m->string = 11;
		$m->bool = 0;
		$m->float = '23.23';
		$m->array = false;
		$m->null = 'not-null';
//		$this->assertSame($m->int, 32);
//		$this->assertSame($m->string, '11');
//		$this->assertSame($m->bool, false);
//		$this->assertSame($m->float, 23.23);
//		$this->assertSame($m->array, [false]);
//		$this->assertSame($m->null, 'not-null');
	}
}
