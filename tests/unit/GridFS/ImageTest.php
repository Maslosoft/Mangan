<?php

namespace GridFS;

use Codeception\TestCase\Test;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\ImageThumb;
use Maslosoft\Mangan\Model\Image;
use Maslosoft\Mangan\Model\ImageParams;
use Maslosoft\ManganTest\Models\GridFS\ModelWithEmbeddedFile;
use UnitTester;

class ImageTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillResizeSavedImage()
	{
		// Temp file location
		$fileName = __DIR__ . '/logo-1024.png';

		$md5 = md5_file($fileName);

		$model = new ModelWithEmbeddedFile();

		$model->file = new Image();

		$model->file->set($fileName);

		$em = new EntityManager($model);
		$em->save();

		$finder = new Finder($model);

		$found = $finder->findByPk($model->_id);

		/* @var $found ModelWithEmbeddedFile */

		$file = $found->file->get()->getBytes();
		$this->assertSame($fileName, $found->file->filename);
		$this->assertSame($md5, md5($file));

		$image = $found->file;

		$params = new ImageParams();
		$params->width = 100;
		$params->height = 100;

		/* @var $image Image */


		$scaledName = tempnam('/tmp/', 'image-test') . '.png';

		$image->get($params)->write($scaledName);

		$this->assertTrue(file_exists($scaledName));

		$gd = new ImageThumb($scaledName);

		$dimensions = (object) $gd->getCurrentDimensions();
		codecept_debug($dimensions);

		$this->assertSame($params->width, $dimensions->width);
		$this->assertSame($params->height, $dimensions->height);
	}

}
