<?php

class EMongoRecordBehavior extends CActiveRecordBehavior
{
	public function events()
	{
		return array_merge(parent::events(), array(
			'onBeforeEmbdedDocsInit'=>'beforeEmbdedDocsInit',
			'onAfterEmbdedDocsInit'=>'afterEmbdedDocsInit'
		));
	}

	public function beforeEmbdedDocsInit($event){}
	public function afterEmbdedDocsInit($event){}
}