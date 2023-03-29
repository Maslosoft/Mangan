<?php

namespace Sanitizers;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Sanitizers\BooleanSanitizer;
use Maslosoft\Mangan\Sanitizers\DateSanitizer;
use Maslosoft\Mangan\Sanitizers\DoubleSanitizer;
use Maslosoft\Mangan\Sanitizers\IntegerSanitizer;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Mangan\Sanitizers\MongoStringId;
use Maslosoft\Mangan\Sanitizers\PassThrough;
use Maslosoft\Mangan\Sanitizers\StringSanitizer;
use Maslosoft\Mangan\Sanitizers\DateReadUnixSanitizer;
use Maslosoft\ManganTest\Models\VoidModel;
use MongoDB\BSON\UTCDateTime as MongoDate;
use MongoDB\BSON\ObjectId as MongoId;
use UnitTester;

class SimpleSanitizersTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfPassProperTypes()
	{
		$model = new VoidModel;
		$sanitizer = new PassThrough;
		$this->assertSame($sanitizer->read($model, 1), 1);
		$this->assertSame($sanitizer->write($model, 1), 1);

		$sanitizer = new StringSanitizer;
		$this->assertSame($sanitizer->read($model, 'foo'), 'foo');
		$this->assertSame($sanitizer->write($model, 'foo'), 'foo');

		$sanitizer = new IntegerSanitizer;
		$this->assertSame($sanitizer->read($model, 1), 1);
		$this->assertSame($sanitizer->write($model, 1), 1);
		$this->assertSame($sanitizer->read($model, '1'), 1);
		$this->assertSame($sanitizer->write($model, '1'), 1);

		$sanitizer = new MongoObjectId;
		$id = new MongoId();
		$this->assertSame($sanitizer->read($model, $id), $id);
		$this->assertSame($sanitizer->write($model, $id), $id);

		$sanitizer = new MongoStringId;
		$id = new MongoId();
		$this->assertSame($sanitizer->read($model, $id), (string) $id);
		$this->assertSame($sanitizer->write($model, $id), (string) $id);

		$this->assertSame($sanitizer->read($model, (string) $id), (string) $id);
		$this->assertSame($sanitizer->write($model, (string) $id), (string)$id);


		$sanitizer = new DoubleSanitizer();
		$this->assertSame($sanitizer->read($model, .1), .1);
		$this->assertSame($sanitizer->write($model, .1), .1);
		$this->assertSame($sanitizer->read($model, '.1'), .1);
		$this->assertSame($sanitizer->write($model, '.1'), .1);

		$sanitizer = new DateSanitizer();
		$date = new MongoDate();
		$this->assertSame($sanitizer->read($model, $date), $date);
		$this->assertSame($sanitizer->write($model, $date), $date);

		$sanitizer = new DateReadUnixSanitizer();
		$date = new MongoDate();
		// NOTE: Need to cast $date->sec to int or hhvm complains about ".0"
		$this->assertSame($sanitizer->read($model, $date->toDateTime()->getTimestamp()), (int) $date->toDateTime()->getTimestamp());
		$this->assertSame($sanitizer->write($model, $date), $date);

		$sanitizer = new BooleanSanitizer();
		$this->assertSame($sanitizer->read($model, true), true);
		$this->assertSame($sanitizer->write($model, true), true);

		$this->assertSame($sanitizer->read($model, 1), true);
		$this->assertSame($sanitizer->write($model, 1), true);
		$this->assertSame($sanitizer->write($model, 0), false);
		$this->assertSame($sanitizer->read($model, 0), false);
		$this->assertSame($sanitizer->read($model, '0'), false);
		$this->assertSame($sanitizer->write($model, '0'), false);
		$this->assertSame($sanitizer->read($model, 'yes'), true);
		$this->assertSame($sanitizer->write($model, ['g']), true);
	}

}
