<?php

namespace Criteria;

use Codeception\Test\Unit;
use Maslosoft\Mangan\DataProvider;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\ManganTest\Models\WithBaseAttributes;
use UnitTester;

class LimitTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function _before()
	{

		for ($i = 0; $i < 100; $i++)
		{
			$model = new WithBaseAttributes();
			$model->int = $i;
			(new EntityManager($model))->insert();
		}
	}

	public function testIfWillLimitDataFromConfig()
	{
		$dp = new DataProvider(new WithBaseAttributes(), ['limit' => 5]);
		$data = $dp->getData();

		$this->assertSame(5, count($data), 'That data was limited to 5');
	}

	public function testIfWillLimitDataFromPagination()
	{
		$dp = new DataProvider(new WithBaseAttributes(), [
			'pagination' => [
				'size' => 5
			]
		]);

		$data = $dp->getData();

		$this->assertSame(5, count($data), 'That data was limited to 5');
	}

	public function testIfWillLimitDataFromPaginationAndAllowScrollingData()
	{
		$dp = new DataProvider(new WithBaseAttributes(), [
			'pagination' => [
				'size' => 5
			]
		]);
		$dp->getPagination()->setPage(2);
		$data = $dp->getData();

		$this->assertSame(5, count($data), 'That data was limited to 5');
		$this->assertSame(5, $data[0]->int, 'That first item of data has value five');
	}

}
