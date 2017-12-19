<?php

namespace Event;

use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;
use Maslosoft\ManganTest\Models\Embedded\WithEmbeddedArrayI18NModel;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use Maslosoft\ManganTest\Models\ModelWithI18NSecond;
use UnitTester;

class PropagateTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfEventWillPropagate()
	{

		$model = new WithEmbeddedArrayI18NModel();

		$m1 = new ModelWithI18N();
		$m2 = new ModelWithI18NSecond();

		$model->pages[] = $m1;

		$model->page = $m2;

		$triggered1 = false;
		$triggered2 = false;

		$beforeSave1 = function(ModelEvent $event)use(&$triggered1)
		{
			$triggered1 = true;
			$event->isValid = true;
		};

		$beforeSave2 = function(ModelEvent $event)use(&$triggered2)
		{
			$triggered2 = true;
			$event->isValid = true;
		};

		Event::on($m1, EntityManagerInterface::EventBeforeSave, $beforeSave1);
		Event::on($m2, EntityManagerInterface::EventBeforeSave, $beforeSave2);

		$saved = $model->save();
		$this->assertTrue($saved);
		$this->assertTrue($triggered1);
		$this->assertTrue($triggered2);
	}

	public function testIfEventWillPropagateAndRevokeSave()
	{

		$model = new WithEmbeddedArrayI18NModel();

		$m1 = new ModelWithI18N();
		$m2 = new ModelWithI18NSecond();

		$model->pages[] = $m1;

		$model->page = $m2;

		$triggered1 = false;
		$triggered2 = false;

		$beforeSave1 = function(ModelEvent $event)use(&$triggered1)
		{
			$triggered1 = true;
			$event->isValid = false;
		};

		$beforeSave2 = function(ModelEvent $event)use(&$triggered2)
		{
			$triggered2 = true;
			$event->isValid = false;
		};

		Event::on($m1, EntityManagerInterface::EventBeforeSave, $beforeSave1);
		Event::on($m2, EntityManagerInterface::EventBeforeSave, $beforeSave2);

		$saved = $model->save();
		$this->assertFalse($saved);
		$this->assertTrue($triggered1);
		$this->assertTrue($triggered2);
	}

		public function testIfEventWillStopPropagateAndAllowAlmostRevokedSave()
	{

		$model = new WithEmbeddedArrayI18NModel();

		$m1 = new ModelWithI18N();
		$m2 = new ModelWithI18NSecond();

		$model->pages[] = $m1;

		$model->page = $m2;

		$triggered = false;
		$triggered1 = false;
		$triggered2 = false;

		$beforeSave = function(ModelEvent $event)use(&$triggered)
		{
			$event->stopPropagation();
			$triggered = true;
			$event->isValid = false;
		};

		$beforeSave1 = function(ModelEvent $event)use(&$triggered1)
		{
			$triggered1 = true;
			$event->isValid = false;
		};

		$beforeSave2 = function(ModelEvent $event)use(&$triggered2)
		{
			$triggered2 = true;
			$event->isValid = false;
		};

		Event::on($model, EntityManagerInterface::EventBeforeSave, $beforeSave);
		Event::on($m1, EntityManagerInterface::EventBeforeSave, $beforeSave1);
		Event::on($m2, EntityManagerInterface::EventBeforeSave, $beforeSave2);

		$saved = $model->save();
		$this->assertFalse($saved);
		$this->assertFalse($triggered1);
		$this->assertFalse($triggered2);
	}
}
