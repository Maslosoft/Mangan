<?php

abstract class EMongoEmbeddedDocument extends CModel
{
	private static $_attributes=array();

	/**
	 * CMap of embedded documents
	 * @var CMap $_embedded
	 */
	protected $_embedded=null;

	/**
	 * Hold down owner pointer (if any)
	 *
	 * @var EMongoEmbeddedDocument $_owner
	 */
	protected $_owner=null;

	/**
	 * Constructor.
	 * @param string $scenario name of the scenario that this model is used in.
	 * See {@link CModel::scenario} on how scenario is used by models.
	 * @see getScenario
	 */
	public function __construct($scenario='insert')
	{
		$this->setScenario($scenario);
		$this->init();
		$this->attachBehaviors($this->behaviors());
		$this->afterConstruct();

		$this->initEmbeddedDocuments();
	}

	/**
	 * Initializes this model.
	 * This method is invoked in the constructor right after {@link scenario} is set.
	 * You may override this method to provide code that is needed to initialize the model (e.g. setting
	 * initial property values.)
	 * @since 1.0.8
	 */
	public function init(){}

	protected function initEmbeddedDocuments()
	{
		if(!$this->hasEmbeddedDocuments() || !$this->beforeEmbeddedDocsInit())
			return false;

		$this->_embedded = new CMap;
		foreach($this->embeddedDocuments() as $name=>$docClassName)
		{
			$doc = new $docClassName($this->getScenario());
			$doc->setOwner($this);
			$this->_embedded->add($name, $doc);
		}
		$this->afterEmbeddedDocsInit();
	}

	public function onBeforeEmbeddedDocsInit($event)
	{
		$this->raiseEvent('onBeforeEmbeddedDocsInit', $event);
	}

	public function onAfterEmbeddedDocsInit($event)
	{
		$this->raiseEvent('onAfterEmbeddedDocsInit', $event);
	}

	public function onBeforeToArray($event)
	{
		$this->raiseEvent('onBeforeToArray', $event);
	}

	public function onAfterToArray($event)
	{
		$this->raiseEvent('onAfterToArray', $event);
	}

	protected function beforeToArray()
	{
		$this->onBeforeToArray(new CModelEvent($this));
		return $event->isValid;
	}

	protected function afterToArray()
	{
		$this->onAfterToArray(new CModelEvent($this));
	}

	protected function beforeEmbeddedDocsInit()
	{
		$event=new CModelEvent($this);
		$this->onBeforeEmbeddedDocsInit($event);
		return $event->isValid;
	}

	protected function afterEmbeddedDocsInit()
	{
		$this->onAfterEmbeddedDocsInit(new CModelEvent());
	}

	public function __get($name)
	{
		if($this->hasEmbeddedDocuments() && $this->_embedded->contains($name))
			return $this->_embedded->itemAt($name);
		else
			return parent::__get($name);
	}

	public function __set($name, $value)
	{
		if($this->hasEmbeddedDocuments() && $this->_embedded->contains($name))
		{
			if(is_array($value))
				return $this->_embedded->itemAt($name)->attributes=$value;
			else if($value instanceof EMongoEmbeddedDocument)
				return $this->_embedded->add($name, $value);
		}
		else
			parent::__set($name, $value);
	}

	public function afterValidate()
	{
		if($this->hasEmbeddedDocuments())
			foreach($this->_embedded as $doc)
			{
				if(!$doc->validate())
				{
					$this->addErrors($doc->getErrors());
				}
			}
	}

	public function embeddedDocuments()
	{
		return array();
	}

	public function hasEmbeddedDocuments()
	{
		return count($this->embeddedDocuments()) > 0;
	}

	/**
	 * Returns the list of attribute names.
	 * By default, this method returns all public properties of the class.
	 * You may override this method to change the default.
	 * @return array list of attribute names. Defaults to all public properties of the class.
	 */
	public function attributeNames()
	{
		$className=get_class($this);
		if(!isset(self::$_attributes[$className]))
		{
			$class=new ReflectionClass(get_class($this));
			$names=array();
			foreach($class->getProperties() as $property)
			{
				$name=$property->getName();
				if($property->isPublic() && !$property->isStatic())
					$names[]=$name;
			}
			if($this->hasEmbeddedDocuments())
			{
				$names = array_merge($names, $this->_embedded->getKeys());
			}
			return self::$_attributes[$className]=$names;
		}
		else
			return self::$_attributes[$className];
	}

	public function toArray()
	{
		if($this->beforeToArray())
		{
			$arr = array();
			foreach($this as $key=>$value)
				$arr[$key]=$value;
			if($this->hasEmbeddedDocuments())
				foreach($this->_embedded as $key=>$value)
					$arr[$key]=$value->toArray();
			$this->afterToArray();
			return $arr;
		}
	}

	/**
	 * Return owner of this document
	 * @return EMongoEmbeddedDocument
	 */
	public function getOwner()
	{
		if($this->_owner!==null)
			return $this->_owner;
		else
			return null;
	}

	/**
	 * Set owner of this document
	 * @param EMongoEmbeddedDocument $owner
	 */
	public function setOwner(EMongoEmbeddedDocument $owner)
	{
		$this->_owner = $owner;
	}

	/**
	 * Override default seScenario method for populating to embedded records
	 * @see CModel::setScenario()
	 */
	public function setScenario($value)
	{
		if($this->hasEmbeddedDocuments() && $this->_embedded !== null)
		{
			foreach($this->_embedded as $doc)
				$doc->setScenario($value);
		}
		parent::setScenario($value);
	}
}