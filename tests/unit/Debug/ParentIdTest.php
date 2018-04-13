<?php

namespace Debug;

use Codeception\Test\Unit;
use Maslosoft\ManganTest\Models\ModelWithEmbeddedModelWithParentId;
use Maslosoft\ManganTest\Models\ModelWithParentId;
use UnitTester;

class ParentIdTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @link https://github.com/Maslosoft/Mangan/issues/72
	 */
	public function testIfWillProperlySetParentIdOnSave()
	{
		$model = new ModelWithEmbeddedModelWithParentId;
		$sub = new ModelWithParentId();
		$model->sub = $sub;

		$saved = $model->save();
		$this->assertTrue($saved, 'That model was saved');

		$this->assertSame((string)$model->_id, (string)$model->sub->parentId, 'That parent id was set on save');
	}

}
