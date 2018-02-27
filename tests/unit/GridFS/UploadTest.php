<?php

namespace GridFS;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\File\Upload;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Model\File;
use Maslosoft\ManganTest\Models\GridFS\ModelWithEmbeddedFile;
use UnitTester;

class UploadTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillUploadFile()
	{
		// Uploaded file name
		$name = 'maslosoft-logo.png';

		// Temp file location
		$tempName = __DIR__ . '/logo-1024.png';

		// Mock $_FILES
		$_FILES['upload'] = [
			'name' => $name,
			'tmp_name' => $tempName,
			'error' => 0
		];

		$upload = new Upload('upload');

		$md5 = md5_file($tempName);

		$model = new ModelWithEmbeddedFile();

		$model->file = new File();

		$model->file->set($upload);

		$em = new EntityManager($model);
		$em->save();

		$finder = new Finder($model);

		$found = $finder->findByPk($model->_id);

		/* @var $found ModelWithEmbeddedFile */

		$file = $found->file->get()->getBytes();
		$this->assertSame($name, $found->file->filename);
		$this->assertSame($md5, md5($file));
	}

}
