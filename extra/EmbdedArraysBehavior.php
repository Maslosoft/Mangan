<?php

class EmbdedArraysBehavior extends EMongoRecordBehavior
{
	/**
	 * Name of property witch holds array od documents
	 *
	 * @var string $arrayPropertyName
	 */
	public $arrayPropertyName;

	/**
	 * Class name of doc in array
	 *
	 * @var string $arrayDocClassName
	 */
	public $arrayDocClassName;

	public function attach($owner)
	{
		parent::attach($owner);

		$this->parseExistingArray();
	}

	/**
	 * Event: initialize array of embded documents
	 */
	public function afterEmbdedDocsInit($event)
	{
		$this->parseExistingArray();
	}

	private function parseExistingArray()
	{
		if(is_array($this->getOwner()->{$this->arrayPropertyName}))
		{
			$arrayOfDocs = array();
			foreach($this->getOwner()->{$this->arrayPropertyName} as $key=>$doc)
			{
				$arrayOfDocs[$key] = new $this->arrayDocClassName;
				$arrayOfDocs[$key]->setAttributes($doc, false);
			}
			$this->getOwner()->{$this->arrayPropertyName} = $arrayOfDocs;
		}
	}

	public function beforeSave($event)
	{
		if(is_array($this->getOwner()->{$this->arrayPropertyName}))
		{
			$arrayOfDocs = array();
			foreach($this->getOwner()->{$this->arrayPropertyName} as $key=>$doc)
			{
				if($this->getOwner()->{$this->arrayPropertyName}[$key]->validate())
				{
					$arrayOfDocs[$key] = $this->getOwner()->{$this->arrayPropertyName}[$key]->toArray();
				}
				else
					return false;
			}
			$this->getOwner()->{$this->arrayPropertyName} = $arrayOfDocs;
			return true;
		}
		else
			return false;
	}
}