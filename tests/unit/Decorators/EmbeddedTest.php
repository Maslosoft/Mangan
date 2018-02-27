<?php

namespace Decorators;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Decorators\EmbeddedDecorator;
use Maslosoft\Mangan\Helpers\Decorator\Decorator;
use Maslosoft\Mangan\Transformers\RawArray;
use Maslosoft\ManganTest\Models\Embedded\WithPlainEmbedded;
use Maslosoft\ManganTest\Models\Plain\SimplePlainEmbedded;
use UnitTester;

class EmbeddedTest extends Unit
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
	public function testIfWillConvertEmbeddedToArrayWithDecorator()
	{
		$model = new WithPlainEmbedded();
		$model->stats = new SimplePlainEmbedded();

		$decorator = new EmbeddedDecorator();

		$dbValue = [
			'stats' => [

			]
		];
		$decorator->write($model, 'stats', $dbValue, RawArray::class);

		$this->assertSame($dbValue['stats']['_class'], get_class($model->stats));
		$this->assertSame($dbValue['stats']['visits'], $model->stats->visits);
	}

	public function testIfWillDecorateWithCompoundDecorator()
	{
		$model = new WithPlainEmbedded();
		$model->stats = new SimplePlainEmbedded();

		$decorator = new Decorator($model, RawArray::class);

		$dbValue = [
			'stats' => [

			]
		];
		$decorator->write('stats', $dbValue);
		
		$this->assertSame($dbValue['stats']['_class'], get_class($model->stats));
		$this->assertSame($dbValue['stats']['visits'], $model->stats->visits);
	}

}
