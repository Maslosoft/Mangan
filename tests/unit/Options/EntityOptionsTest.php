<?php

namespace Options;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Options\EntityOptions;
use Maslosoft\ManganTest\Models\ModelWithClientFlags;
use Maslosoft\ManganTest\Models\VoidModel;

class EntityOptionsTest extends Test
{

	public function testCanReadGlobal()
	{
		$model = new VoidModel;
		$o = new EntityOptions($model);
		$mangan = new Mangan();
		$this->assertNotNull($mangan->w);
		$this->assertNotNull($o->w);
		$this->assertSame($o->w, $mangan->w);
		$o->w = 'majority';
		$this->assertSame($o->w, 'majority');
	}

	public function testCanReadFromModelAnnotation()
	{
		// From annotation:
		// w = 3, fsync = true
		$model = new ModelWithClientFlags();
		$o = new EntityOptions($model);
		$meta = ManganMeta::create($model)->type();
		$this->assertSame($o->fsync, true);
		$this->assertSame($o->w, 3);
	}

	public function testCanGetSetLocal()
	{
		$model = new VoidModel;
		$o = new EntityOptions($model);
		$o->w = 1;
		$this->assertSame(1, $o->w);
		$o->w = 0;
		$this->assertSame(0, $o->w);
	}

}
