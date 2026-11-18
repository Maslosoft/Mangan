<?php

namespace Options;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Options\EntityOptions;
use Maslosoft\ManganTest\Models\ModelWithClientFlags;
use Maslosoft\ManganTest\Models\VoidModel;

class EntityOptionsTest extends Unit
{

	public function testCanReadGlobal()
	{
		$model = new VoidModel;
		$o = new EntityOptions($model);
		$mangan = new Mangan();
		$this->assertNull($mangan->w);
		$this->assertNull($o->w);
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

    public function testWriteConcernInheritsUriAndInjectedClient(): void
    {
        $mangan = new Mangan('write-concern-regression');
        $mangan->connectionString = 'mongodb://127.0.0.1/?w=majority&wTimeoutMS=321&journal=true';
        $this->assertSame('majority', $mangan->getManager()->getWriteConcern()->getW());
        $options = new EntityOptions(new VoidModel(), $mangan);
        $this->assertArrayNotHasKey('writeConcern', $options->getSaveOptions());
        $options->w = 2;
        $concern = $options->getSaveOptions()['writeConcern'];
        $this->assertSame(2, $concern->getW());
        $this->assertSame(321, $concern->getWtimeout());
        $this->assertTrue($concern->getJournal());
        unset($options->w);
        $mangan->setConnection(new \MongoDB\Client('mongodb://127.0.0.1/?w=majority'));
        $this->assertArrayNotHasKey('writeConcern', $options->getSaveOptions());
        $options->journal = false;
        $options->wTimeoutMS = 100;
        $concern = $options->getSaveOptions()['writeConcern'];
        $this->assertSame('majority', $concern->getW());
        $this->assertFalse($concern->getJournal());
        $this->assertSame(100, $concern->getWtimeout());
        $mangan->setConnection(new \MongoDB\Client());
        $this->assertNull($options->getSaveOptions()['writeConcern']->getW());
    }

    public function testExplicitConnectionWriteConcernOverridesUri(): void
    {
        $mangan = new Mangan('explicit-write-concern');
        $mangan->connectionString = 'mongodb://127.0.0.1/?w=majority';
        $mangan->w = 2;
        $this->assertSame(2, $mangan->getManager()->getWriteConcern()->getW());
    }

}
