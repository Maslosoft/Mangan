<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 07.03.18
 * Time: 13:19
 */

namespace Maslosoft\ManganTest\Extensions;


use Maslosoft\Mangan\Criteria;

class CriteriaMergingTester
{
	private $tester = null;
	public function __construct($tester)
	{
		$this->tester = $tester;
	}

	public function test($criteria, $newCriteria)
	{
		$this->doTest($criteria, $newCriteria);
		$this->doTest($newCriteria, $criteria);
	}

	private function doTest($criteria, $newCriteria)
	{
		// Only IDE hints, as params might be various
		// later
		/* @var $criteria Criteria */
		/* @var $newCriteria Criteria */
		$criteria->addCond('title', '==', 'Test');

		$conditions = $criteria->getConditions();
		codecept_debug($conditions);

		$this->tester->assertCount(1, $conditions, 'One condition was created');

		$this->tester->assertArrayHasKey('title.en', $conditions, 'Is decorated with EN');

		$newCriteria->mergeWith($criteria);

		$newConditions = $newCriteria->getConditions();
		codecept_debug($newConditions);

		$this->tester->assertArrayHasKey('title.en', $newConditions, 'New is also decorated with EN');
	}
}