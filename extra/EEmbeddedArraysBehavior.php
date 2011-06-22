<?php
/**
 * EEmbeddedArraysBehavior.php
 *
 * PHP version 5.2+
 *
 * @author		Dariusz Górecki <darek.krk@gmail.com>
 * @author		Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright	2011 CleverIT http://www.cleverit.com.pl
 * @license		http://www.yiiframework.com/license/ BSD license
 * @version		1.3
 * @category	ext
 * @package		ext.YiiMongoDbSuite
 * @since		v1.0
 */

/**
 * @since v1.0
 */
class EEmbeddedArraysBehavior extends EMongoDocumentBehavior
{
	/**
	 * Name of property witch holds array od documents
	 *
	 * @var string $arrayPropertyName
	 * @since v1.0
	 */
	public $arrayPropertyName;

	/**
	 * Class name of doc in array
	 *
	 * @var string $arrayDocClassName
	 * @since v1.0
	 */
	public $arrayDocClassName;

	private $_cache;

	/**
	 * @since v1.0
	 * @see CBehavior::attach()
	 */
	public function attach($owner)
	{
		parent::attach($owner);

		// Test if we have correct embding class
		if(!is_subclass_of($this->arrayDocClassName, 'EMongoEmbeddedDocument'))
			throw new CException(Yii::t('yii', get_class($testObj).' is not a child class of EMongoEmbeddedDocument!'));

		$this->parseExistingArray();
	}

	/**
	 * Event: initialize array of embded documents
	 * @since v1.0
	 */
	public function afterEmbeddedDocsInit($event)
	{
		$this->parseExistingArray();
	}

	/**
	 * @since v1.0
	 */
	private function parseExistingArray()
	{
		if(is_array($this->getOwner()->{$this->arrayPropertyName}))
		{
			$arrayOfDocs = array();
			foreach($this->getOwner()->{$this->arrayPropertyName} as $doc)
			{
				$obj = new $this->arrayDocClassName;
				$obj->setAttributes($doc, false);
				$arrayOfDocs[] = $obj;
			}
			$this->getOwner()->{$this->arrayPropertyName} = $arrayOfDocs;
		}
	}

	/**
	 * @since v1.0.2
	 */
	public function afterValidate($event)
	{
		parent::afterValidate($event);
		foreach($this->getOwner()->{$this->arrayPropertyName} as $doc)
		{
			if(!$doc->validate())
				$this->getOwner()->addErrors($doc->getErrors());
		}
	}

	public function beforeToArray($event)
	{
		if(is_array($this->getOwner()->{$this->arrayPropertyName}))
		{
			$arrayOfDocs = array();
			$this->_cache = $this->getOwner()->{$this->arrayPropertyName};

			foreach($this->_cache as $doc)
			{
				$arrayOfDocs[] = $doc->toArray();
			}

			$this->getOwner()->{$this->arrayPropertyName} = $arrayOfDocs;
			return true;
		}
		else
			return false;
	}

	/**
	 * Event: re-initialize array of embedded documents which where toArray()ized by beforeSave()
	 */
	public function afterToArray($event)
	{
		$this->getOwner()->{$this->arrayPropertyName} = $this->_cache;
		$this->_cache = null;
	}
}
