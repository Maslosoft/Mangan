<?php

namespace Pagination;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\DataProvider;
use Maslosoft\Mangan\Interfaces\PaginationInterface;
use Maslosoft\Mangan\Pagination;
use Maslosoft\ManganTest\Models\Pagination\ModelForPagination;
use UnitTester;

class PaginationTest extends Test
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
	public function testWillProperlyCalculatePages()
	{
		$pagination = new Pagination;
		$pagination->setSize(10);
		$pagination->setCount(25);

		$this->assertSame(3, $pagination->getPages(), 'That there are 3 pages');
	}

	public function testWillProperlyCalculateLimitAndOffset()
	{
		$pagination = new Pagination;
		$pagination->setSize(25);
		$pagination->setCount(100);

		$this->assertSame(0, $pagination->getOffset(), 'That should start from 0');
		$this->assertSame(25, $pagination->getLimit(), 'That should limit to 25');

		// Set second page
		$pagination->setPage(2);

		$this->assertSame(25, $pagination->getOffset(), 'That should start from 25');
		$this->assertSame(25, $pagination->getLimit(), 'That should limit to 25');
	}

	public function testWillProperlyCalculateLimitAndOffsetToNotGoOutOfRange()
	{
		#
		# Pagination should revert to max or min page if value is out of range #78
		# https://github.com/Maslosoft/Mangan/issues/78
		#
		$pagination = new Pagination;
		$pagination->setSize(25);
		$pagination->setCount(100);

		$this->assertSame(0, $pagination->getOffset(), 'That should start from 0');
		$this->assertSame(25, $pagination->getLimit(), 'That should limit to 25');

		// Set ninth, non existent page
		$pagination->setPage(9);

		$this->assertSame(4, $pagination->getPages(), 'That should have max 4 pages');
		$this->assertSame(4, $pagination->getPage(), 'That pagination fall back to last page');
		$this->assertSame(75, $pagination->getOffset(), 'That should start from 75');
		$this->assertSame(25, $pagination->getLimit(), 'That should limit to 25');

		// Set minus nine, non existent page
		$pagination->setPage(-9);

		$this->assertSame(4, $pagination->getPages(), 'That should have max 4 pages');
		$this->assertSame(1, $pagination->getPage(), 'That pagination fall back to first page');
		$this->assertSame(0, $pagination->getOffset(), 'That should start from 0');
		$this->assertSame(25, $pagination->getLimit(), 'That should limit to 25');
	}

	public function testWillProperlyCalculateLimitAndOffsetToNotGoOutOfRangeWithZeroCount()
	{
		#
		# Pagination should revert to max or min page if value is out of range #78
		# https://github.com/Maslosoft/Mangan/issues/78
		#
		$pagination = new Pagination;
		$pagination->setSize(25);
		$pagination->setCount(0);

		$this->assertSame(0, $pagination->getOffset(), 'That should start from 0');
		$this->assertSame(25, $pagination->getLimit(), 'That should limit to 25');

		// Set ninth, non existent page
		$pagination->setPage(9);

		$this->assertSame(0, $pagination->getPages(), 'That should have max 0 pages');
		$this->assertSame(1, $pagination->getPage(), 'That pagination fall back to last page');
		$this->assertSame(0, $pagination->getOffset(), 'That should start from 0');
		$this->assertSame(25, $pagination->getLimit(), 'That should limit to 25');

		// Set minus nine, non existent page
		$pagination->setPage(-9);

		$this->assertSame(0, $pagination->getPages(), 'That should have max 1 pages');
		$this->assertSame(1, $pagination->getPage(), 'That pagination fall back to first page');
		$this->assertSame(0, $pagination->getOffset(), 'That should start from 0');
		$this->assertSame(25, $pagination->getLimit(), 'That should limit to 25');
	}

	public function testIfWillProperlyWorkWithRealData()
	{
		$this->prepareData();
		$model = new ModelForPagination;
		$dp = new DataProvider($model);

		// First page, should auto initialize pagination
		$data = $dp->getData();
		/* @var $data ModelForPagination[] */
		$this->assertSame(PaginationInterface::DefaultPageSize, count($data), 'That data count was limited by default pagination limit');
		$this->assertSame(1, $data[0]->number);
		$this->assertSame(25, $data[24]->number);
		$this->assertFalse(isset($data[25]));

		// Third page
		$dp->getPagination()->setPage(3);
		$data2 = $dp->getData(true);
		$this->assertSame(PaginationInterface::DefaultPageSize, count($data2), 'That data count was limited by default pagination limit');
		$this->assertSame(51, $data2[0]->number);
		$this->assertSame(75, $data2[24]->number);
		$this->assertFalse(isset($data2[25]));

		// Out of range - over max page
		$dp->getPagination()->setPage(90);
		$data2 = $dp->getData(true);
		$this->assertSame(PaginationInterface::DefaultPageSize, count($data2), 'That data count was limited by default pagination limit');
		$this->assertSame(76, $data2[0]->number);
		$this->assertSame(100, $data2[24]->number);
		$this->assertFalse(isset($data2[25]));

		// Out of range - negative page
		$dp->getPagination()->setPage(-90);
		$data2 = $dp->getData(true);
		$this->assertSame(PaginationInterface::DefaultPageSize, count($data2), 'That data count was limited by default pagination limit');
		$this->assertSame(1, $data2[0]->number);
		$this->assertSame(25, $data2[24]->number);
		$this->assertFalse(isset($data2[25]));
	}

	private function prepareData()
	{
		for ($i = 0; $i < 100; $i++)
		{
			$model = new ModelForPagination;
			$model->number = $i + 1;
			$model->save();
		}
		codecept_debug("Max num: $i");
	}

}
