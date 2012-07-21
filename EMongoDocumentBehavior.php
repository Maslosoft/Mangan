<?php

/**
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @license New BSD license
 * @version 1.3
 * @category ext
 * @package ext.YiiMongoDbSuite
 */

/**
 * EMongoDocumentBehavior
 *
 * @since v1.0
 */
class EMongoDocumentBehavior extends CActiveRecordBehavior
{

	public function events()
	{
		return array_merge(parent::events(), array(
					'onBeforeEmbeddedDocsInit' => 'beforeEmbeddedDocsInit',
					'onAfterEmbeddedDocsInit' => 'afterEmbeddedDocsInit',
					'onBeforeToArray' => 'beforeToArray',
					'onAfterToArray' => 'afterToArray'
				));
	}

	public function beforeEmbeddedDocsInit($event)
	{

	}

	public function afterEmbeddedDocsInit($event)
	{

	}

	public function beforeToArray($event)
	{

	}

	public function afterToArray($event)
	{

	}

}