<?php

namespace Event;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Model\Trash;
use Maslosoft\ManganTest\Models\Event\ChildDocument;
use Maslosoft\ManganTest\Models\Event\ParentDocument;
use UnitTester;

class ParentChildTrashableTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function _before()
	{
		// Ensure that trash is empty
		(new Trash)->deleteAll();
	}

	// tests
	public function testIfWillProperlyTrashAllChildItems()
	{
		$parent = new ParentDocument;
		$child = new ChildDocument;
		$this->makeSomeItems($parent, $child);

		$this->assertTrue($parent->trash(), "That parent was trashed");

		$this->assertSame($parent->count(), 0, 'That there are no parent items');

		$this->assertSame($child->count(), 0, 'That there are no child items');

		$this->assertSame((new Trash)->count(), 4, 'That there are total of 4 items in trash');

		$this->assertSame((new Trash)->purge(), 4, 'That 4 items were removed from trash');
	}

	public function testIfWillRestoreChildItems()
	{
		$parent = new ParentDocument;
		$child = new ChildDocument;
		$trashed = new Trash;
		$this->makeSomeItems($parent, $child);

		$this->assertTrue($parent->trash(), "That parent was trashed");

		$this->assertSame((new Trash)->count(), 4, 'That there are 4 items in trash');
		$this->assertSame($child->count(), 0, 'That there are no child items');
		$this->assertSame($parent->count(), 0, 'That there are no parent items');

		$this->getTrashed($parent, $trashed);

		$this->assertTrue($trashed->restore(), "That parent was restored");

		$this->assertSame((new Trash)->count(), 0, 'That there are no items in trash');

		$this->assertSame($child->count(), 3, 'That there are 3 child items');

		$this->assertSame((new Trash)->purge(), 0, 'That no items were removed from trash');
	}

	public function testIfWillLeavePreviouslyTrashedItemInTrash()
	{
		$parent = new ParentDocument;
		$child = new ChildDocument;
		$trashed = new Trash;
		$this->makeSomeItems($parent, $child);

		$this->assertTrue($child->trash(), 'That child item was trashed');

		$this->assertSame((new Trash)->count(), 1, 'That there are one item in trash');

		// Ensure that some time has passed
		sleep(1);

		$this->assertTrue($parent->trash(), "That parent was trashed");

		$this->assertSame((new Trash)->count(), 4, 'That there are 4 items in trash');
		$this->assertSame($child->count(), 0, 'That there are no child items');
		$this->assertSame($parent->count(), 0, 'That there are no parent items');

		$this->getTrashed($parent, $trashed);

		$this->assertTrue($trashed->restore(), "That parent was restored");

		$this->assertSame((new Trash)->count(), 1, 'That there are one item left in trash');

		$this->assertSame($child->count(), 2, 'That there are 2 child items (one should be left in trash)');

		$this->assertSame((new Trash)->purge(), 1, 'That 1 item was removed from trash');
	}

	public function testIfWillPreventRestoringChildItemIfParentWasNotRestored()
	{
		$parent = new ParentDocument;
		$child = new ChildDocument;
		$trashed = new Trash;
		$this->makeSomeItems($parent, $child);

		$this->assertTrue($parent->trash(), "That parent was trashed");

		$this->assertSame((new Trash)->count(), 4, 'That there are 4 items in trash');
		$this->assertSame($child->count(), 0, 'That there are no child items');
		$this->assertSame($parent->count(), 0, 'That there are no parent items');

		$this->getTrashed($child, $trashed);

		$this->assertNotNull($trashed, 'That trashed child item exists');

		$this->assertFalse($trashed->restore(), "That child was not restored");
	}

	private function makeSomeItems(&$parent, &$child)
	{
		$parent->title = 'parent';
		foreach ([1, 2, 3]as $i)
		{
			$child = new ChildDocument();
			$child->title = "Child #$i";
			$child->parentId = $parent->_id;
			$this->assertTrue($child->save(), "That child item #$i was saved");
		}

		$this->assertTrue($parent->save(), "That parent was saved");
	}

	private function getTrashed($item, &$trashed)
	{
		$criteria = new Criteria();
		$criteria->addCond('data._id', '==', $item->_id);
		$trashed = (new Trash)->find($criteria);
	}

}
