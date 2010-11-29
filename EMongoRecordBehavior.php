<?php

class EMongoRecordBehavior extends CActiveRecordBehavior
{
	public function events()
	{
		return array_merge(parent::events(), array(
			'onBeforeEmbeddedDocsInit'=>'beforeEmbeddedDocsInit',
			'onAfterEmbeddedDocsInit'=>'afterEmbeddedDocsInit'
		));
	}

	public function beforeEmbeddedDocsInit($event){}
	public function afterEmbeddedDocsInit($event){}
}