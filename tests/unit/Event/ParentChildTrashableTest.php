<?php

namespace Event;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Criteria;
use Maslosoft\Mangan\Events\Handlers\ParentChildTrashHandler;
use Maslosoft\Mangan\Model\Trash;
use Maslosoft\ManganTest\Extensions\ParentChildTrashTester;
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
		$h = new ParentChildTrashHandler;
		$h->parentClass = ParentDocument::class;
		$h->childClass = ChildDocument::class;
		$h->setupHandlers();
		(new Trash())->deleteAll();
	}

	public function testTrashing()
	{
		$tester = new ParentChildTrashTester($this, new ParentDocument, new ChildDocument);
		$tester->run();
	}

	public function setupChild($child, $i)
	{
		$child->title = "Child #$i";
	}

	public function setupParent($parent)
	{
		$parent->title = 'parent';
	}
}
