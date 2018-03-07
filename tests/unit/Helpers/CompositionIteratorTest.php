<?php

namespace Helpers;

use Maslosoft\Mangan\Helpers\CompositionIterator;
use Maslosoft\ManganTest\Models\DbRef\ModelWithUpdatableDbRef;
use Maslosoft\ManganTest\Models\Tree\ModelWithSimpleTree;
use Maslosoft\ManganTest\Models\Validator\EmbeddedModelWithValidator;
use Maslosoft\ManganTest\Models\Validator\ModelWithDbRefArrayWithValidator;

class CompositionIteratorTest extends \Codeception\Test\Unit
{
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	/**
	 * @var ModelWithSimpleTree
	 */
	private $model = null;

	protected function _before()
	{
		$model = new ModelWithSimpleTree;
		$sub = new ModelWithDbRefArrayWithValidator;

		$withRef = new ModelWithUpdatableDbRef;
		$withRef->stats = new EmbeddedModelWithValidator;
		$sub->addresses = [
			$withRef,
			new EmbeddedModelWithValidator
		];

		$model->children = [
			$sub
		];
		$this->model = $model;
	}

	protected function _after()
	{
	}

	// tests
	public function testIteratorDefaults()
	{
		$shouldHave = [
			ModelWithDbRefArrayWithValidator::class,
			ModelWithUpdatableDbRef::class,
			EmbeddedModelWithValidator::class,
			EmbeddedModelWithValidator::class
		];
		$this->check(
			new CompositionIterator($this->model),
			$shouldHave
		);
	}

	public function testIteratorOfType()
	{
		$shouldHave = [
			EmbeddedModelWithValidator::class,
			EmbeddedModelWithValidator::class
		];
		$this->check(
			(new CompositionIterator($this->model))
				->ofType(EmbeddedModelWithValidator::class),
			$shouldHave
		);
	}

	public function testIteratorDirect()
	{
		$shouldHave = [
			ModelWithDbRefArrayWithValidator::class,
		];
		$this->check(
			(new CompositionIterator($this->model))
				->direct(),
			$shouldHave
		);
	}

	public function testIteratorDirectOfType()
	{
		$shouldHave = [
			ModelWithDbRefArrayWithValidator::class,
		];
		$this->check(
			(new CompositionIterator($this->model))
				->ofType(ModelWithDbRefArrayWithValidator::class)
				->direct(),
			$shouldHave
		);
	}

	public function testIteratorDirectOfTypeNotMatching()
	{

		$shouldHave = [
		];
		$this->check(
			(new CompositionIterator($this->model))
				->ofType(EmbeddedModelWithValidator::class)
				->direct(),
			$shouldHave
		);
	}

	private function check(CompositionIterator $iterator, $shouldHave)
	{
		$count = count($shouldHave);

		$this->assertCount($count, $iterator, 'That Countable returns same value');

		$classes = [];
		foreach ($iterator as $model)
		{
			$class = get_class($model);
			$classes[] = $class;
			codecept_debug($class);
		}

		$this->assertCount($count, $classes, "Has $count sub objects");

		foreach($shouldHave as $className)
		{
			$parts = explode('\\', $className);
			$shortName = array_pop($parts);
			$this->assertContains($className, $classes, "Have class `$shortName` in list");

			// Remove checked class, so that duplicated one
			// will not yield false-positive
			$key = array_search($className, $classes);
			unset($classes[$key]);
		}
	}
}