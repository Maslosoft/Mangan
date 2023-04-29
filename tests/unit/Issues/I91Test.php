<?php


namespace Unit\Issues;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Criteria;
use Maslosoft\ManganTest\Models\Issues\Model91;
use MongoDB\BSON\ObjectId;

class I91Test extends Unit
{

	/**
	 * Finder::find does not take offset into account
	 * @return void
	 * @link https://github.com/Maslosoft/Mangan/issues/91
	 */
	public function testIfOffsetIsUsedInCriteriaForFindOne(): void
	{
		$data = [
			(string)new ObjectId() => 'Test_1',
			(string)new ObjectId() => 'Test_2',
			(string)new ObjectId() => 'Test_3',
		];
		foreach ($data as $key => $value)
		{
			$model = new Model91();
			$model->_id = new ObjectId($key);
			$model->title = $value;
			$saved = $model->save();
			$this->assertTrue($saved, 'Model was saved successfully');
		}
		$model = new Model91();
		$criteria = new Criteria(null, $model);
		$criteria->offset(2);
		$criteria->sort('title', Criteria::SortAsc);
		$found = $model->find($criteria);
		codecept_debug($found->title);
		$this->assertNotNull($found, 'Document was found');
		$this->assertSame('Test_3', $found->title, 'The offsetted document was found');
	}
}
