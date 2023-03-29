<?php

namespace Event;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Interfaces\EntityManagerInterface;
use Maslosoft\ManganTest\Models\ActiveDocument\DocumentBaseAttributes;
use MongoDB\BSON\ObjectId;
use UnitTester;

class EntityManagerTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillTriggerBeforeSave()
	{
		$triggered = false;

		$model = new DocumentBaseAttributes();

		$beforeSave = function(ModelEvent $event)use(&$triggered)
		{
			$triggered = true;
			$event->isValid = true;
		};

		Event::on($model, EntityManagerInterface::EventBeforeSave, $beforeSave);

		$saved = $model->save();
		$this->assertTrue($saved);
		$this->assertTrue($triggered);

		Event::off($model, EntityManagerInterface::EventBeforeSave, $beforeSave);
	}

	public function testIfWillTriggerBeforeSaveAndRevokeSave()
	{
		$triggered = false;

		$model = new DocumentBaseAttributes();

		$beforeSave = function(ModelEvent $event)use(&$triggered)
		{
			$triggered = true;
			$event->isValid = false;
		};

		Event::on($model, EntityManagerInterface::EventBeforeSave, $beforeSave);

		$saved = $model->save();
		$this->assertFalse($saved, 'That model was not saved');
		$this->assertTrue($triggered, 'That event was triggered');

		Event::off($model, EntityManagerInterface::EventBeforeSave, $beforeSave);
	}

	public function testIfWillTriggerAfterSave()
	{
		$triggered = false;

		$model = new DocumentBaseAttributes();

		$afterSave = function(ModelEvent $event)use(&$triggered)
		{
			$triggered = true;
		};

		Event::on($model, EntityManagerInterface::EventAfterSave, $afterSave);

		$saved = $model->save();
		$this->assertTrue($saved);
		$this->assertTrue($triggered);

		Event::off($model, EntityManagerInterface::EventAfterSave, $afterSave);
	}

	public function testIfWillTriggerBeforeInsert()
	{
		$triggered = false;

		$model = new DocumentBaseAttributes();

		$beforeSave = function(ModelEvent $event)use(&$triggered)
		{
			$triggered = true;
		};

		Event::on($model, EntityManagerInterface::EventBeforeInsert, $beforeSave);

		$saved = $model->insert();
		$this->assertTrue($saved);
		$this->assertTrue($triggered);

		Event::off($model, EntityManagerInterface::EventBeforeInsert, $beforeSave);
	}

	public function testIfWillTriggerAfterInsert()
	{
		$triggered = false;

		$model = new DocumentBaseAttributes();

		$beforeSave = function(ModelEvent $event)use(&$triggered)
		{
			$triggered = true;
		};

		Event::on($model, EntityManagerInterface::EventAfterInsert, $beforeSave);

		$saved = $model->insert();
		$this->assertTrue($saved);
		$this->assertTrue($triggered);

		Event::off($model, EntityManagerInterface::EventAfterInsert, $beforeSave);
	}

	public function testIfWillTriggerBeforeUpdate()
	{
		$triggered = false;

		$model = new DocumentBaseAttributes();

		$beforeSave = function(ModelEvent $event)use(&$triggered)
		{
			$triggered = true;
		};

		Event::on($model, EntityManagerInterface::EventBeforeUpdate, $beforeSave);

		$model->save();
		$saved = $model->update();
		$this->assertTrue($saved);
		$this->assertTrue($triggered);

		Event::off($model, EntityManagerInterface::EventBeforeUpdate, $beforeSave);
	}

	public function testIfWillTriggerAfterUpdate()
	{
		$triggered = false;

		$model = new DocumentBaseAttributes();

		$beforeSave = function(ModelEvent $event)use(&$triggered)
		{
			$triggered = true;
		};

		Event::on($model, EntityManagerInterface::EventAfterUpdate, $beforeSave);

		$model->save();
		$saved = $model->update();
		$this->assertTrue($saved);
		$this->assertTrue($triggered);

		Event::off($model, EntityManagerInterface::EventAfterUpdate, $beforeSave);
	}

	public function testIfWillTriggerBeforeDelete()
	{
		$triggered = false;

		$model = new DocumentBaseAttributes();

		$beforeDelete = function(ModelEvent $event)use(&$triggered)
		{
			$triggered = true;
			$event->isValid = true;
		};

		Event::on($model, EntityManagerInterface::EventBeforeDelete, $beforeDelete);

		$saved = $model->save();
		$deleted = $model->delete();
		$this->assertTrue($deleted);
		$this->assertTrue($triggered);

		Event::off($model, EntityManagerInterface::EventBeforeDelete, $beforeDelete);
	}

	public function testIfWillTriggerBeforeDeleteAndRevokeDelete()
	{
		$triggered = false;

		$model = new DocumentBaseAttributes();
		$model->_id = new ObjectId;

		$beforeDelete = function(ModelEvent $event)use(&$triggered)
		{
			$triggered = true;
			$event->isValid = false;
		};

		Event::on($model, EntityManagerInterface::EventBeforeDelete, $beforeDelete);

		$saved = $model->save();
		$deleted = $model->delete();
		$this->assertFalse($deleted);
		$this->assertFalse($model->deleteByPk($model->_id));
		$this->assertFalse($model->deleteAllByPk([$model->_id]));
		$this->assertTrue($triggered);

		Event::off($model, EntityManagerInterface::EventBeforeDelete, $beforeDelete);
	}

	public function testIfWillTriggerAfterDelete()
	{
		$triggered = false;

		$model = new DocumentBaseAttributes();

		$afterDelete = function(ModelEvent $event)use(&$triggered)
		{
			$triggered = true;
		};

		Event::on($model, EntityManagerInterface::EventAfterDelete, $afterDelete);

		$saved = $model->save();
		$deleted = $model->delete();
		$this->assertTrue($deleted);
		$this->assertTrue($triggered);

		Event::off($model, EntityManagerInterface::EventAfterDelete, $afterDelete);
	}

}
