<?php

namespace TestExtensions;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Transformers\RawArray;
use Maslosoft\ManganTest\Extensions\ModelComparator;
use Maslosoft\ManganTest\Models\Embedded\WithEmbeddedArrayI18NModel;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use UnitTester;

class ModelComparatorTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillCompareModel(): void
	{
		$langs = [];
		$model = new WithEmbeddedArrayI18NModel();

		// Attach first
		$m = new ModelWithI18N;
		$m->setLang('en');
		$m->_id = new \MongoId;
		$m->setLanguages($langs);
		$m->layout = 'new';
		$m->title = 'New York';
		$m->setLang('pl');
		$m->title = 'Nowy Jork';
		$m->setLang('en');
		$model->pages[] = $m;

		// Attach second
		$m2 = new ModelWithI18N;
		$m2->setLang('en');
		$m2->_id = new \MongoId;
		$m2->setLanguages($langs);
		$m2->layout = 'new';
		$m2->title = 'Prague';
		$m2->setLang('pl');
		$m2->title = 'Praga';
		$m2->setLang('en');
		$model->pages[] = $m2;

		$comparator = new ModelComparator($this);
		$data = RawArray::fromModel($model);

		foreach ([0, 1] as $i)
		{
			$this->assertSame($model->pages[$i]->_id, $data['pages'][$i]['_id']);
			$this->assertSame($model->pages[$i]->title, $data['pages'][$i]['title']['en']);
			$this->assertSame($model->pages[$i]->layout, $data['pages'][$i]['layout']);
		}
		$comparator->compare($data, $model);
	}

}
