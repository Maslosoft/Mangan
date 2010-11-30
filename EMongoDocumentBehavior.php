<?php

class EMongoDocumentBehavior extends CActiveRecordBehavior
{
	public function events()
	{
		return array_merge(parent::events(), array(
			'onBeforeEmbeddedDocsInit'=>'beforeEmbeddedDocsInit',
			'onAfterEmbeddedDocsInit'=>'afterEmbeddedDocsInit',
			'onBeforeToArray'=>'beforeToArray',
			'onAfterToArray'=>'afterToArray'
		));
	}

	public function beforeEmbeddedDocsInit($event){}
	public function afterEmbeddedDocsInit($event){}
	public function beforeToArray($event){}
	public function afterToArray($event){}
}