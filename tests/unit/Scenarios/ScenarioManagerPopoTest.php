<?php

namespace Scenarios;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Interfaces\ScenariosInterface;
use Maslosoft\Mangan\ScenarioManager;
use Maslosoft\ManganTest\Models\ScenarioManager\BaseAttributesScenario;
use UnitTester;

class ScenarioManagerPopoTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillSetInsertScenarioOnNewObject()
	{
		$model = new BaseAttributesScenario();
		$this->assertSame(ScenarioManager::getScenario($model), ScenariosInterface::Insert);
	}

	public function testIfWillSetUpdateScenarioOnSavedObject()
	{
		$model = new BaseAttributesScenario();
		(new EntityManager($model))->save();
		$this->assertSame(ScenarioManager::getScenario($model), ScenariosInterface::Update);
	}

	public function testIfWillSetUpdateScenarioOnFoundObject()
	{
		$model = new BaseAttributesScenario();
		(new EntityManager($model))->save();
		$found = (new Finder($model))->findByPk($model->_id);
		$this->assertSame(ScenarioManager::getScenario($found), ScenariosInterface::Update);
	}

	public function testIfWillSetDeleteScenarioOnDeletedObject()
	{
		$model = new BaseAttributesScenario();
		(new EntityManager($model))->save();
		(new EntityManager($model))->delete();
		$this->assertSame(ScenarioManager::getScenario($model), ScenariosInterface::Delete);
	}

}
