<?php

namespace GridFS;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Model\File;
use Maslosoft\Mangan\Model\Image;
use Maslosoft\Mangan\Model\ImageParams;
use Maslosoft\ManganTest\Models\GridFS\ModelWithEmbeddedFile;
use Maslosoft\ManganTest\Models\GridFS\ModelWithEmbeddedImage;
use UnitTester;

class EmbeddedFileTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function _before()
	{
		new File();
	}

	protected function _after()
	{

	}

	// tests
	public function testIfWillEmbedFile()
	{
		$fileName = __FILE__;

		$md5 = md5_file($fileName);

		$model = new ModelWithEmbeddedFile();

		$model->file = new File();

		$model->file->set($fileName);

		$em = new EntityManager($model);
		$em->save();

		$finder = new Finder($model);

		$found = $finder->findByPk($model->_id);

		/* @var $found ModelWithEmbeddedFile */

		$file = $found->file->get()->getBytes();

		$this->assertSame($md5, md5($file));
	}

	public function testIfWillDeleteEmbeddedFile()
	{
		$fileName = __FILE__;

		$md5 = md5_file($fileName);

		// NOTE: Must work fine even if _id is not set
		$model = new ModelWithEmbeddedFile();

		$model->file = new File();
		$model->file->set($fileName);

		$em = new EntityManager($model);
		$em->save();

		$finder = new Finder($model);

		$found = $finder->findByPk($model->_id);

		/* @var $found ModelWithEmbeddedFile */

		$file = $found->file->get()->getBytes();

		$this->assertSame($md5, md5($file));

		$mangan = new Mangan();

		$gf = $mangan->getDbInstance()->getGridFS();

		$criteria = [
			'parentId' => $found->file->_id
		];

		$this->assertSame(1, $gf->count($criteria));

		$deleted = $found->delete();

		$this->assertTrue($deleted);

		$this->assertSame(0, $gf->count($criteria));
	}

	public function testIfWillDeleteEmbeddedImage()
	{
		$fileName = __DIR__ . '/logo-1024.png';

		$md5 = md5_file($fileName);

		// NOTE: Must work fine even if _id is not set
		$model = new ModelWithEmbeddedImage();

		$model->file = new Image();
		$model->file->set($fileName);

		$em = new EntityManager($model);
		$em->save();

		$finder = new Finder($model);

		$found = $finder->findByPk($model->_id);

		/* @var $found ModelWithEmbeddedImage */

		$file = $found->file->get()->getBytes();

		$this->assertSame($md5, md5($file));

		// Resize image
		$params = new ImageParams();
		$params->width = 100;
		$params->height = 100;
		$resized = $found->file->get($params)->getBytes();

		// Check if was resized
		$this->assertTrue($file > $resized);

		$mangan = new Mangan();

		$gfs = $mangan->getDbInstance()->getGridFS();

		$tmp = $mangan->getDbInstance()->getGridFS(File::TmpPrefix);

		$criteria = [
			'parentId' => $found->file->_id
		];

		$this->assertSame(1, $gfs->count($criteria));
		$this->assertSame(1, $tmp->count($criteria));

		$deleted = $found->delete();

		$this->assertTrue($deleted);

		$this->assertSame(0, $gfs->count($criteria));
		$this->assertSame(0, $tmp->count($criteria));
	}

}
