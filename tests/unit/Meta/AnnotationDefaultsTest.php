<?php

namespace Meta;

use Maslosoft\Mangan\Annotations\I18NAnnotation;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use ReflectionProperty;

class AnnotationDefaultsTest extends \Codeception\Test\Unit
{
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	private $originalConfig = null;

	protected function _before()
	{
		$this->originalConfig = Mangan::fly()->annotationsDefaults;

	}

	protected function _after()
	{
		Mangan::fly()->annotationsDefaults = $this->originalConfig;
	}

	// tests
	public function testIfWillApplyDefaultValues()
	{
		$value = true;
		$this->setTo($value);
		$model = new ModelWithI18N;

		$target = new ReflectionProperty($model, 'title');
		$annotation = new I18NAnnotation([], $target);

		$this->assertSame($value, $annotation->allowAny, 'That `allowAny` has value taken from config');
		$this->assertSame($value, $annotation->allowDefault, 'That `allowDefault` has value taken from config');

		// To ensure that it works, now change value
		$value = false;
		$this->setTo($value);

		$annotation = new I18NAnnotation([], $target);

		$this->assertSame($value, $annotation->allowAny, 'That `allowAny` has value taken from config');
		$this->assertSame($value, $annotation->allowDefault, 'That `allowDefault` has value taken from config');
	}

	private function setTo($value)
	{
		Mangan::fly()->annotationsDefaults = [
			I18NAnnotation::class => [
				'allowAny' => $value,
				'allowDefault' => $value
			]
		];
	}
}