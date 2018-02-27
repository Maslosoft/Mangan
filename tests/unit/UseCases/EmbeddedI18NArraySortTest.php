<?php

namespace UseCases;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Transformers\JsonArray;
use Maslosoft\Mangan\Transformers\RawArray;
use Maslosoft\ManganTest\Extensions\ModelComparator;
use Maslosoft\ManganTest\Models\Embedded\WithEmbeddedArrayI18NModel;
use Maslosoft\ManganTest\Models\ModelWithI18N;
use Maslosoft\ManganTest\Models\ModelWithI18NSecond;
use UnitTester;

class EmbeddedI18NArraySortTest extends Unit
{

	use \Codeception\Specify;

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * This is use case, where model is initialized from external JSON
	 * And order of elements are changed in json.
	 */
	public function testIfWillProperlyStoreI18NFieldsWhenChangedOrderFromExternalSource()
	{
		$langs = [
			'en', 'pl'
		];
		$model = new WithEmbeddedArrayI18NModel();

		// Attach single
		$one = new ModelWithI18N;
		$one->setLanguages($langs);
		$one->layout = 'new';
		$one->title = 'New York';
		$one->setLang('pl');
		$one->title = 'Nowy Jork';
		$one->setLang('en');
		$model->page = $one;

		// Attach first
		$m = new ModelWithI18N;
		$m->setLanguages($langs);
		$m->layout = 'new';
		$m->title = 'New York';
		$m->setLang('pl');
		$m->title = 'Nowy Jork';
		$m->setLang('en');
		$model->pages[] = $m;

		// Attach second
		$m = new ModelWithI18NSecond();
		$m->setLanguages($langs);
		$m->layout = 'new';
		$m->title = 'Prague';
		$m->setLang('pl');
		$m->title = 'Praga';
		$m->setLang('en');
		$model->pages[] = $m;

		// This are expected values
		$expectedData = RawArray::fromModel($model);

		$expectedData['pages'] = array_reverse($expectedData['pages']);

		// Now assume that external json data arrived
		$externalData = JsonArray::fromModel($model);

		$externalData['pages'] = array_reverse($externalData['pages']);

		$expectedModel = JsonArray::toModel($externalData, $model, $model);

		$comparator = new ModelComparator($this);
		$comparator->compare($expectedData, $expectedModel);
		$raw = RawArray::fromModel($expectedModel);
	}

	private function _apply(WithEmbeddedArrayI18NModel $model, $data)
	{
		foreach ($data as $i => $field)
		{
			foreach ($field as $code => $title)
			{
				$model->setLang($code);
				if (!isset($model->pages[$i]))
				{
					$model->pages[$i] = new ModelWithI18N();
				}
				$model->pages[$i]->title = $title;
			}
		}
	}

	private function _check(WithEmbeddedArrayI18NModel $model, $data)
	{
		foreach ($data as $i => $field)
		{
			foreach ($field as $code => $title)
			{
				$msg = sprintf("When model key is `%d` and language is `%s`", $i, $code);
				$model->setLang($code);
				$this->assertSame($title, $model->pages[$i]->title, $msg);
			}
		}
	}

}
