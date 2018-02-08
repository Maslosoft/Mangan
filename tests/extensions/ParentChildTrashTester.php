<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 08.02.18
 * Time: 11:17
 */

namespace Maslosoft\ManganTest\Extensions;


use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\PkManager;
use Maslosoft\Mangan\Interfaces\TrashInterface;
use Maslosoft\Mangan\Model\Trash;
use ReflectionClass;

class ParentChildTrashTester
{
	private $tester = null;
	private $parent = null;
	private $child = null;
	private $trash = null;

	public function __construct($tester, AnnotatedInterface $parent, AnnotatedInterface $child, $trash = null)
	{
		$this->tester = $tester;
		$this->parent = $parent;
		$this->child = $child;
		if(empty($trash))
		{
			$trash = new Trash;
		}
		$this->trash = $trash;

		$info = new ReflectionClass($this->tester);

		$this->tester->assertTrue($info->hasMethod('setupParent'), '$tester need setupParent method');
		$this->tester->assertTrue($info->hasMethod('setupChild'), '$tester need setupChild method');
	}

	public function run()
	{
		$this->checkHandler($this->parent, TrashInterface::EventAfterTrash);
		$this->checkHandler($this->parent, TrashInterface::EventAfterRestore);
		$this->checkHandler($this->child, TrashInterface::EventBeforeRestore);
		foreach((new ReflectionClass($this))->getMethods() as $method)
		{
			$name = $method->name;
			if(strpos($name, 'test') === 0)
			{
				codecept_debug($name);
				// Ensure that trash is empty
				$trash = $this->newTrash();
				$trash->deleteAll();

				$this->parent->deleteAll();
				$this->child->deleteAll();

				$numTrash = $trash->count();
				$numParent = $this->parent->count();
				$numChild = $this->child->count();

				$this->tester->assertSame(0, $numTrash, 'Trash cleaned');
				$this->tester->assertSame(0, $numParent, 'Parent cleaned');
				$this->tester->assertSame(0, $numChild, 'Child cleaned');
				$this->$name();
			}
		}
	}

	// tests
	private function testIfWillProperlyTrashAllChildItems()
	{
		$parent = $this->newParent();
		$child = $this->newChild();
		$this->makeSomeItems($parent, $child);

		$this->tester->assertTrue($parent->trash(), "That parent was trashed");

		$this->tester->assertSame($parent->count(), 0, 'That there are no parent items');

		$this->tester->assertSame($child->count(), 0, 'That there are no child items');

		$this->tester->assertSame($this->newTrash()->count(), 4, 'That there are total of 4 items in trash');

		$this->tester->assertSame($this->newTrash()->purge(), 4, 'That 4 items were removed from trash');
	}

	private function testIfWillRestoreChildItems()
	{
		$parent = $this->newParent();
		$child = $this->newChild();
		$trashed = $this->newTrash();
		$this->makeSomeItems($parent, $child);

		$this->tester->assertTrue($parent->trash(), "That parent was trashed");

		$this->tester->assertSame($this->newTrash()->count(), 4, 'That there are 4 items in trash');
		$this->tester->assertSame($child->count(), 0, 'That there are no child items');
		$this->tester->assertSame($parent->count(), 0, 'That there are no parent items');

		$this->getTrashed($parent, $trashed);

		$this->tester->assertTrue($trashed->restore(), "That parent was restored");

		$this->tester->assertSame($this->newTrash()->count(), 0, 'That there are no items in trash');

		$this->tester->assertSame($child->count(), 3, 'That there are 3 child items');

		$this->tester->assertSame($this->newTrash()->purge(), 0, 'That no items were removed from trash');
	}

	private function testIfWillLeavePreviouslyTrashedItemInTrash()
	{
		$parent = $this->newParent();
		$child = $this->newChild();
		$trashed = $this->newTrash();
		$this->makeSomeItems($parent, $child);

		$this->tester->assertTrue($child->trash(), 'That child item was trashed');

		$this->tester->assertSame($this->newTrash()->count(), 1, 'That there are one item in trash');

		$this->tester->assertSame($child->count(), 2, 'Items left');

		// NOTE: Need to wait a bit, as there is a time condition
		// in trash handler
		sleep(1);

		$trashResult = $parent->trash();
		$this->tester->assertTrue($trashResult, "That parent was trashed");

		$this->tester->assertSame($this->newTrash()->count(), 4, 'That there are 4 items in trash');
		$this->tester->assertSame($child->count(), 0, 'That there are no child items');
		$this->tester->assertSame($parent->count(), 0, 'That there are no parent items');

		$this->getTrashed($parent, $trashed);

		$this->tester->assertTrue($trashed->restore(), "That parent was restored");

		$this->tester->assertSame($this->newTrash()->count(), 1, 'That there are one item left in trash');

		$this->tester->assertSame($child->count(), 2, 'That there are 2 child items (one should be left in trash)');

		$this->tester->assertSame($this->newTrash()->purge(), 1, 'That 1 item was removed from trash');
	}

	private function testIfWillPreventRestoringChildItemIfParentWasNotRestored()
	{
		$parent = $this->newParent();
		$child = $this->newChild();
		$trashed = $this->newTrash();
		$this->makeSomeItems($parent, $child);

		$this->tester->assertTrue($parent->trash(), "That parent was trashed");

		$this->tester->assertSame($this->newTrash()->count(), 4, 'That there are 4 items in trash');
		$this->tester->assertSame($child->count(), 0, 'That there are no child items');
		$this->tester->assertSame($parent->count(), 0, 'That there are no parent items');

		$this->getTrashed($child, $trashed);

		$this->tester->assertNotNull($trashed, 'That trashed child item exists');

		$this->tester->assertFalse($trashed->restore(), "That child was not restored");
	}

	private function makeSomeItems(&$parent, &$child)
	{
		$this->tester->setupParent($parent);
		foreach ([1, 2, 3] as $i)
		{
			$child = $this->newChild();
			$this->tester->setupChild($child, $i);

			$child->parentId = $parent->_id;
			$saved = $child->save();
			$this->tester->assertTrue($saved, "That child item #$i was saved");

			$exists = (new Finder($child))->exists(PkManager::prepareFromModel($child));
			$this->tester->assertTrue($exists, "That child item #$i really exists");
		}
		$this->tester->assertSame($i, (new Finder($child))->count(), "Only $i items exists");
		$this->tester->assertTrue($parent->save(), "That parent was saved");
	}

	private function checkHandler($model, $eventName)
	{
		$this->tester->assertTrue(Event::hasHandler($model, $eventName), sprintf('Class %s has %s handler', get_class($model), $eventName));
	}

	private function getTrashed($item, &$trashed)
	{
		$criteria = new Criteria();
		$criteria->addCond('data._id', '==', $item->_id);
		$trashed = $this->newTrash()->find($criteria);
	}

	private function newChild()
	{
		$class = get_class($this->child);
		return new $class();
	}

	private function newParent()
	{
		$class = get_class($this->parent);
		return new $class();
	}

	/**
	 * @return Trash
	 */
	private function newTrash()
	{
		$class = get_class($this->trash);
		return new $class();
	}
}