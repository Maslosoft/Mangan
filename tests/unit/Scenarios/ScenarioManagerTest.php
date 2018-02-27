<?php

namespace Scenarios;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Interfaces\ScenariosInterface;
use Maslosoft\Mangan\ScenarioManager;
use Maslosoft\ManganTest\Models\ActiveDocument\DocumentBaseAttributes;
use UnitTester;

class ScenarioManagerTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillSetInsertScenarioOnNewObject()
	{
		$model = new DocumentBaseAttributes();
		$this->assertSame(ScenarioManager::getScenario($model), ScenariosInterface::Insert);
	}

	public function testIfWillSetUpdateScenarioOnSavedObject()
	{
		$model = new DocumentBaseAttributes();
		$model->save();
		$this->assertSame(ScenarioManager::getScenario($model), ScenariosInterface::Update);
	}

	public function testIfWillSetUpdateScenarioOnFoundObject()
	{
		$model = new DocumentBaseAttributes();
		$model->save();
		$found = $model->findByPk($model->_id);
		$this->assertSame(ScenarioManager::getScenario($found), ScenariosInterface::Update);
	}

	public function testIfWillSetDeleteScenarioOnDeletedObject()
	{
		$model = new DocumentBaseAttributes();
		$model->save();
		$model->delete();
		$this->assertSame(ScenarioManager::getScenario($model), ScenariosInterface::Delete);
	}

}
