<?php

namespace Debug;

use Codeception\TestCase\Test;
use Maslosoft\ManganTest\Models\ModelWithEmbeddedModelWithParentId;
use Maslosoft\ManganTest\Models\ModelWithParentId;
use PHPUnit_Framework_IncompleteTestError;
use UnitTester;

class ParentIdTest extends Test
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
		throw new PHPUnit_Framework_IncompleteTestError('This test has incomplete feature');
		$model = new ModelWithEmbeddedModelWithParentId;
		$sub = new ModelWithParentId();
		$model->sub = $sub;

		$saved = $model->save();
		$this->assertTrue($saved, 'That model was saved');

		$this->assertSame((string) $model->_id, (string) $model->sub->parentId, 'That parent id was set on save');
	}

}
