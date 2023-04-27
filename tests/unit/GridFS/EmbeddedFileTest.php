<?php

namespace GridFS;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Model\File;
use Maslosoft\Mangan\Model\Image;
use Maslosoft\Mangan\Model\ImageParams;
use Maslosoft\ManganTest\Models\GridFS\ModelWithEmbeddedFile;
use Maslosoft\ManganTest\Models\GridFS\ModelWithEmbeddedImage;
use MongoDB\GridFS\Bucket;
use UnitTester;

class EmbeddedFileTest extends Unit
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
	public function testIfWillEmbedFile(): void
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

	public function testIfWillDeleteEmbeddedFile(): void
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

		$bucket = $mangan->getDbInstance()->selectGridFSBucket();

		$criteria = [
			'parentId' => $found->file->_id
		];
		$this->assertCount(1, $this->getItemsFromBucket($bucket, $criteria));

		$deleted = $found->delete();

		$this->assertTrue($deleted);

		$this->assertCount(0, $this->getItemsFromBucket($bucket, $criteria));
	}

	public function testIfWillDeleteEmbeddedImage(): void
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

		$bucket = $mangan->getDbInstance()->selectGridFSBucket();

		$tmpBucket = $mangan->getDbInstance()->selectGridFSBucket(['prefix' => File::TmpPrefix]);

		$criteria = [
			'parentId' => $found->file->_id
		];

		$this->assertCount(1, $this->getItemsFromBucket($bucket, $criteria));
		$this->assertCount(1, $this->getItemsFromBucket($tmpBucket, $criteria));

		$deleted = $found->delete();

		$this->assertTrue($deleted);

		$this->assertCount(0, $this->getItemsFromBucket($bucket, $criteria));
		$this->assertCount(0, $this->getItemsFromBucket($tmpBucket, $criteria));
	}

	private function getItemsFromBucket(Bucket $bucket, array $criteria): array
	{
		$items = [];
		foreach($bucket->find($criteria) as $item)
		{
			$items[] = $item;
		}
		return $items;
	}

}
