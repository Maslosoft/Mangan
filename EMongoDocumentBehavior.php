<?php
/**
 * EMongoDocumentBehavior.php
 *
 * PHP version 5.2+
 *
 * @author		Dariusz Górecki <darek.krk@gmail.com>
 * @copyright	2010 CleverIT
 * @license		http://www.yiiframework.com/license/ BSD license
 * @version		1.3
 * @category	ext
 * @package		ext.YiiMongoDbSuite
 *
 */

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