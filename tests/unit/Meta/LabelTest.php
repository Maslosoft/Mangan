<?php

namespace Meta;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\ManganTest\Models\ModelWithLabel;
use Maslosoft\ManganTest\Models\Plain\PlainModelWithLabel;
use UnitTester;

class LabelTest extends Unit
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
	public function testIfLabelIsSet()
	{
		$model = new ModelWithLabel();
		$meta = ManganMeta::create($model);

		$this->assertSame(ModelWithLabel::TitleLabel, $meta->title->label);
		$this->assertSame(ModelWithLabel::StateLabel, $meta->state->label);
	}

	public function testIfLabelIsSetInPlainModel()
	{
		$model = new PlainModelWithLabel();
		$meta = ManganMeta::create($model);

		$this->assertSame(ModelWithLabel::TitleLabel, $meta->title->label);
		$this->assertSame(ModelWithLabel::TitleLabel, $meta->title->label);
		$this->assertSame(ModelWithLabel::StateLabel, $meta->state->label);
	}



}
