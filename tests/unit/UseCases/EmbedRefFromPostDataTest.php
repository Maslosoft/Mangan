<?php

namespace UseCases;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Transformers\SafeArray;
use Maslosoft\ManganTest\Models\DbRef\AssetCollection;
use Maslosoft\ManganTest\Models\DbRef\AssetGroup;
use Maslosoft\ManganTest\Models\DbRef\PageAsset;
use Maslosoft\ManganTest\Models\UseCases\FirstWidget;
use Maslosoft\ManganTest\Models\UseCases\PageCell;
use Maslosoft\ManganTest\Models\UseCases\PageCells;
use Maslosoft\ManganTest\Models\UseCases\SecondWidget;
use Maslosoft\ManganTest\Models\UseCases\ThirdWidget;
use UnitTester;

class EmbedRefFromPostDataTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillProperlyCreateAndSaveEmbedRefsFromPostData()
	{
		// POST data from real application
		$data = [
			'items' => [
				0 => [
					'title' => '',
					'description' => '',
					'hasText' => false,
					'items' => [
						0 => [
							'filename' => '',
							'file' => [
								'width' => 0,
								'height' => 0,
								'filename' => '',
								'size' => 0,
								'rootClass' => '',
								'rootId' => '',
								'contentType' => '',
								'_id' => '56336cccc79fda857b8b4b0e',
								'_class' => 'Maslosoft\\Mangan\\Model\\Image',
								'meta' => [
								],
								'rawI18N' => [
								],
							],
							'basename' => '',
							'relativeName' => '',
							'icon' => '/css/filetypes/512/_blank.png',
							'isImage' => false,
							'iconSize' => 512,
							'path' => '',
							'url' => '/assets/get/56336cccc79fda857b8b4b06',
							'type' => '',
							'deleted' => false,
							'title' => '',
							'description' => '',
							'id' => '56336cccc79fda857b8b4b06',
							'createUser' => NULL,
							'createDate' => 0,
							'updateUser' => NULL,
							'updateDate' => 0,
							'rawI18N' => [
								'title' => [
									'en' => '',
								],
								'description' => [
									'en' => '',
								],
							],
							'_id' => '56336cccc79fda857b8b4b06',
							'_class' => 'Maslosoft\\ManganTest\\Models\\DbRef\\PageAsset',
							'meta' => [
							],
							'parentId' => NULL,
						],
						1 => [
							'filename' => '',
							'file' => [
								'width' => 0,
								'height' => 0,
								'filename' => '',
								'size' => 0,
								'rootClass' => '',
								'rootId' => '',
								'contentType' => '',
								'_id' => '56336cccc79fda857b8b4b0f',
								'_class' => 'Maslosoft\\Mangan\\Model\\Image',
								'meta' => [
								],
								'rawI18N' => [
								],
							],
							'basename' => '',
							'relativeName' => '',
							'icon' => '/css/filetypes/512/_blank.png',
							'isImage' => false,
							'iconSize' => 512,
							'path' => '',
							'url' => '/assets/get/56336cccc79fda857b8b4b08',
							'type' => '',
							'deleted' => false,
							'title' => '',
							'description' => '',
							'id' => '56336cccc79fda857b8b4b08',
							'createUser' => NULL,
							'createDate' => 0,
							'updateUser' => NULL,
							'updateDate' => 0,
							'rawI18N' => [
								'title' => [
									'en' => '',
								],
								'description' => [
									'en' => '',
								],
							],
							'_id' => '56336cccc79fda857b8b4b08',
							'_class' => 'Maslosoft\\ManganTest\\Models\\DbRef\\PageAsset',
							'meta' => [
							],
							'parentId' => NULL,
						],
						2 => [
							'filename' => '',
							'file' => [
								'width' => 0,
								'height' => 0,
								'filename' => '',
								'size' => 0,
								'rootClass' => '',
								'rootId' => '',
								'contentType' => '',
								'_id' => '56336cccc79fda857b8b4b10',
								'_class' => 'Maslosoft\\Mangan\\Model\\Image',
								'meta' => [
								],
								'rawI18N' => [
								],
							],
							'basename' => '',
							'relativeName' => '',
							'icon' => '/css/filetypes/512/_blank.png',
							'isImage' => false,
							'iconSize' => 512,
							'path' => '',
							'url' => '/assets/get/56336cccc79fda857b8b4b0a',
							'type' => '',
							'deleted' => false,
							'title' => '',
							'description' => '',
							'id' => '56336cccc79fda857b8b4b0a',
							'createUser' => NULL,
							'createDate' => 0,
							'updateUser' => NULL,
							'updateDate' => 0,
							'rawI18N' => [
								'title' => [
									'en' => '',
								],
								'description' => [
									'en' => '',
								],
							],
							'_id' => '56336cccc79fda857b8b4b0a',
							'_class' => 'Maslosoft\\ManganTest\\Models\\DbRef\\PageAsset',
							'meta' => [
							],
							'parentId' => NULL,
						],
					],
					'assetsCount' => 0,
					'id' => '56336cccc79fda857b8b4b0b',
					'createUser' => NULL,
					'createDate' => 0,
					'updateUser' => NULL,
					'updateDate' => 0,
					'rawI18N' => [
						'title' => [
							'en' => '',
						],
						'description' => [
							'en' => '',
						],
					],
					'_id' => '56336cccc79fda857b8b4b0b',
					'_class' => 'Maslosoft\\ManganTest\\Models\\DbRef\\AssetGroup',
					'meta' => [
					],
					'parentId' => '',
				],
				1 => [
					'title' => 'Some title',
					'description' => '',
					'hasText' => true,
					'items' => [
					],
					'assetsCount' => 0,
					'id' => '56336cccc79fda857b8b4b0c',
					'createUser' => NULL,
					'createDate' => 0,
					'updateUser' => NULL,
					'updateDate' => 0,
					'rawI18N' => [
						'title' => [
							'en' => 'Some title',
						],
						'description' => [
							'en' => '',
						],
					],
					'_id' => '56336cccc79fda857b8b4b0c',
					'_class' => 'Maslosoft\\ManganTest\\Models\\DbRef\\AssetGroup',
					'meta' => [
					],
					'parentId' => '',
				],
			],
			'title' => '',
			'description' => '',
			'groupCount' => 0,
			'assetsCount' => 0,
			'id' => '56336cccc79fda857b8b4b0d',
			'createUser' => NULL,
			'createDate' => 0,
			'updateUser' => NULL,
			'updateDate' => 0,
			'rawI18N' => [
				'title' => [
					'en' => '',
				],
				'description' => [
					'en' => '',
				],
			],
			'_id' => '56336cccc79fda857b8b4b0d',
			'_class' => 'Maslosoft\\ManganTest\\Models\\DbRef\\AssetCollection',
			'meta' => [
			],
		];

		$model = SafeArray::toModel($data);

		$handler = function(ModelEvent $event)
		{
			$event->isValid = true;
			codecept_debug('EntityManager::EventBeforeSave');
		};
		Event::on($model, EntityManager::EventBeforeSave, $handler);

		$this->assertTrue($model instanceof AssetCollection);

		$this->assertSame(2, count($model->items));

		$this->assertTrue($model->items[0] instanceof AssetGroup);
		$this->assertTrue($model->items[1] instanceof AssetGroup);

		$this->assertSame(3, count($model->items[0]->items));

		$this->assertTrue($model->items[0]->items[0] instanceof PageAsset);
		$this->assertTrue($model->items[0]->items[1] instanceof PageAsset);
		$this->assertTrue($model->items[0]->items[2] instanceof PageAsset);

		codecept_debug(get_class($model->items[0]));

		/* @var $model AssetCollection */

		$saved = $model->save();

		$this->assertTrue($saved);

		$found = $model->findByPk($model->id);

		$this->assertNotNull($found);

		$this->assertTrue($found instanceof AssetCollection);

		$this->assertSame(2, count($found->items));

		$this->assertTrue($found->items[0] instanceof AssetGroup);
	}

	public function testIfWillProperlyUpdateInstanceWithChangedClassWithEmbedRefsFromPostData()
	{
		$model = new PageCell();

		$firstPost = [
			'_id' => '56336cccc79fda857b8b4b0d',
			'_class' => PageCell::class,
			'widget' => [
				'_class' => FirstWidget::class
			]
		];

		SafeArray::toModel($firstPost, null, $model);

		$this->assertInstanceOf(PageCell::class, $model);
		$this->assertInstanceOf(FirstWidget::class, $model->widget);

		$secondPost = [
			'_id' => '56336cccc79fda857b8b4b0d',
			'_class' => PageCell::class,
			'widget' => [
				'_class' => SecondWidget::class
			]
		];

		SafeArray::toModel($secondPost, null, $model);

		$this->assertInstanceOf(PageCell::class, $model);
		$this->assertInstanceOf(SecondWidget::class, $model->widget);
	}

	public function testIfWillProperlyUpdateInstanceWithChangedClassesWithEmbedRefsFromPostData()
	{
		$model = new PageCells();

		$firstPost = [
			'_id' => '56336cccc79fda857b8b4b0d',
			'_class' => PageCells::class,
			'widgets' => [
				[
					'_id' => '56336ccbc79fda857b8b4afe',
					'_class' => FirstWidget::class
				]
			]
		];

		SafeArray::toModel($firstPost, null, $model);

		$this->assertInstanceOf(PageCells::class, $model);
		$this->assertInstanceOf(FirstWidget::class, $model->widgets[0]);

		$secondPost = [
			'_id' => '56336cccc79fda857b8b4b0d',
			'_class' => PageCells::class,
			'widgets' => [
				[
					'_id' => '56336ccbc79fda857b8b4afe',
					'_class' => SecondWidget::class
				]
			]
		];

		SafeArray::toModel($secondPost, null, $model);

		$this->assertInstanceOf(PageCells::class, $model);
		$this->assertInstanceOf(SecondWidget::class, $model->widgets[0]);
	}

	public function testIfWillProperlyUpdateInstanceWithReorderedClassesAndOneOfDifferentTypeWithEmbedRefsFromPostData()
	{
		$model = new PageCells();

		$firstPost = [
			'_id' => '56336cccc79fda857b8b4b0d',
			'_class' => PageCells::class,
			'widgets' => [
				[
					'_id' => '56336ccbc79fda857b8b4afe',
					'_class' => FirstWidget::class
				],
				[
					'_id' => '56336ccbc79fda857b8b4aff',
					'_class' => SecondWidget::class
				]
			]
		];

		SafeArray::toModel($firstPost, null, $model);

		$this->assertInstanceOf(PageCells::class, $model);
		$this->assertInstanceOf(FirstWidget::class, $model->widgets[0]);
		$this->assertInstanceOf(SecondWidget::class, $model->widgets[1]);

		$secondPost = [
			'_id' => '56336cccc79fda857b8b4b0d',
			'_class' => PageCells::class,
			'widgets' => [
				[
					'_id' => '56336ccbc79fda857b8b4aff',
					'_class' => ThirdWidget::class
				],
				[
					'_id' => '56336ccbc79fda857b8b4afe',
					'_class' => FirstWidget::class
				]
			]
		];

		SafeArray::toModel($secondPost, null, $model);

		$this->assertInstanceOf(PageCells::class, $model);
		$this->assertInstanceOf(ThirdWidget::class, $model->widgets[0]);
		$this->assertInstanceOf(FirstWidget::class, $model->widgets[1]);
	}

}
