<?php
namespace Helpers;


use function codecept_debug;
use Maslosoft\Mangan\Command;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Helpers\CollectionNamer;
use Maslosoft\Mangan\Helpers\IndexManager;
use Maslosoft\ManganTest\Models\Indexes\ModelWith2dSphere;
use Maslosoft\ManganTest\Models\Indexes\ModelWith2dSphereExtendedNotation;
use Maslosoft\ManganTest\Models\Indexes\ModelWithCompoundI18NIndex;
use Maslosoft\ManganTest\Models\Indexes\ModelWithCompoundI18NIndexShortNotation;
use Maslosoft\ManganTest\Models\Indexes\ModelWithHashedIndex;
use Maslosoft\ManganTest\Models\Indexes\ModelWithI18NIndex;
use Maslosoft\ManganTest\Models\Indexes\ModelWithSimpleIndex;

class IndexManagerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests

	public function testStoragePath()
	{
		$model = new ModelWithSimpleIndex;
		$path = IndexManager::fly()->getStoragePath($model, get_class($model));
		codecept_debug($path);
		$this->assertNotEmpty($path, 'That path exists');
	}

	public function testSimpleIndexCreation()
    {
    	$model = new ModelWithSimpleIndex;
    	$success = IndexManager::fly()->create($model);

    	$indexes = $this->showIndexes($model);

    	$this->assertArrayHasKey('title_1', $indexes);
    	$this->assertTrue($success, 'That index was created');
    }

	public function testI18NIndexCreation()
	{
		$model = new ModelWithI18NIndex;
		$model->setLanguages(['en', 'pl', 'es']);
		$success = IndexManager::fly()->create($model);

		$indexes = $this->showIndexes($model);

		$this->assertArrayHasKey('title_en_1', $indexes);
		$this->assertArrayHasKey('title_pl_1', $indexes);
		$this->assertArrayHasKey('title_es_1', $indexes);
		$this->assertArrayHasKey('title_en_-1', $indexes);
		$this->assertArrayHasKey('title_pl_-1', $indexes);
		$this->assertArrayHasKey('title_es_-1', $indexes);

		$this->assertTrue($success, 'That index was created');
	}

	public function testCompoundI18NIndexCreation()
	{
		$model = new ModelWithCompoundI18NIndex;
		$model->setLanguages(['en', 'pl', 'es']);
		$success = IndexManager::fly()->create($model);

		$indexes = $this->showIndexes($model);

		$this->assertArrayHasKey('username_en_1_email_1', $indexes);
		$this->assertArrayHasKey('username_pl_1_email_1', $indexes);
		$this->assertArrayHasKey('username_en_1_email_1', $indexes);
		$this->assertArrayHasKey('username_en_-1_email_-1', $indexes);
		$this->assertArrayHasKey('username_pl_-1_email_-1', $indexes);
		$this->assertArrayHasKey('username_en_-1_email_-1', $indexes);


		$this->assertTrue($success, 'That index was created');
	}

	public function testCompoundI18NShortNotationIndexCreation()
	{
		$model = new ModelWithCompoundI18NIndexShortNotation;
		$model->setLanguages(['en', 'pl', 'es']);
		$success = IndexManager::fly()->create($model);

		$indexes = $this->showIndexes($model);

		codecept_debug($indexes);

		$this->assertArrayHasKey('username_en_1_email_1', $indexes);
		$this->assertArrayHasKey('username_pl_1_email_1', $indexes);
		$this->assertArrayHasKey('username_en_1_email_1', $indexes);
		$this->assertArrayHasKey('username_en_-1_email_-1', $indexes);
		$this->assertArrayHasKey('username_pl_-1_email_-1', $indexes);
		$this->assertArrayHasKey('username_en_-1_email_-1', $indexes);


		$this->assertTrue($success, 'That index was created');
	}

	public function testHashedIndexCreation()
	{
		$model = new ModelWithHashedIndex;
		$model->setLanguages(['en', 'pl', 'es']);
		$success = IndexManager::fly()->create($model);

		$indexes = $this->showIndexes($model);

		$this->assertArrayHasKey('url_hashed', $indexes);

		$this->assertTrue($success, 'That index was created');
	}

	public function test2dSphereIndexCreation()
	{
		$model = new ModelWith2dSphere;
		$success = IndexManager::fly()->create($model);

		$indexes = $this->showIndexes($model);

		$this->assertArrayHasKey('loc_type_2dsphere', $indexes);

		$this->assertTrue($success, 'That index was created');
	}

	public function test2dSphereIndexExtentedNotationCreation()
	{
		$model = new ModelWith2dSphereExtendedNotation;
		$success = IndexManager::fly()->create($model);

		$indexes = $this->showIndexes($model);

		$this->assertArrayHasKey('loc_type_2dsphere', $indexes);

		$this->assertTrue($success, 'That index was created');
	}

	public function testSimpleIndexAutoCreation()
	{
		$model = new ModelWithSimpleIndex;
		(new Finder($model))->find();

		$indexes = $this->showIndexes($model);

		$this->assertArrayHasKey('title_1', $indexes);
	}

	private function showIndexes($model)
	{
		$cmd = new Command($model);
		$stats = $cmd->collStats(CollectionNamer::nameCollection($model));
		$this->assertArrayHasKey('indexDetails', $stats, 'There are indices in ' . get_class($model));
		$idxs = $stats['indexDetails'];
		$formatted = [];
		foreach($idxs as $name => $meta)
		{
			$info = json_decode($meta['metadata']['infoObj'], JSON_OBJECT_AS_ARRAY);
			$formatted[$name] = [
				'name' => $info['name'],
				'key' => $info['key']
			];
		}
		codecept_debug($formatted);
		return $formatted;
	}
}