<?php

abstract class EMongoEmbdedDocument extends CModel
{
	private static $_names=array();

	/**
	 * CMap of embded documents
	 * @var CMap $_embded
	 */
	protected $_embded=null;

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

		$this->initEmbdedDocuments();
	}

	/**
	 * Initializes this model.
	 * This method is invoked in the constructor right after {@link scenario} is set.
	 * You may override this method to provide code that is needed to initialize the model (e.g. setting
	 * initial property values.)
	 * @since 1.0.8
	 */
	public function init(){}

	protected function initEmbdedDocuments()
	{
		if(!$this->hasEmbdedDocuments() || !$this->beforeEmbdedDocsInit())
			return false;

		$this->_embded = new CMap;
		foreach($this->embdedDocuments() as $name=>$docClassName)
		{
			$this->_embded->add($name, new $docClassName($this->getScenario()));
		}
		$this->afterEmbdedDocsInit();
	}

	public function onBeforeEmbdedDocsInit($event)
	{
		$this->raiseEvent('onBeforeEmbdedDocsInit', $event);
	}

	public function onAfterEmbdedDocsInit($event)
	{
		$this->raiseEvent('onAfterEmbdedDocsInit', $event);
	}

	protected function beforeEmbdedDocsInit()
	{
		$event=new CModelEvent($this);
		$this->onBeforeEmbdedDocsInit($event);
		return $event->isValid;
	}

	protected function afterEmbdedDocsInit()
	{
		$this->onAfterEmbdedDocsInit(new CModelEvent());
	}

	public function __get($name)
	{
		if($this->hasEmbdedDocuments() && $this->_embded->contains($name))
			return $this->_embded->itemAt($name);
		else
			return parent::__get($name);
	}

	public function __set($name, $value)
	{
		if(
			$this->hasEmbdedDocuments() &&
			$this->_embded->contains($name)
		)
		{
			if(is_array($value))
				return $this->_embded->itemAt($name)->attributes=$value;
			else if($value instanceof EMongoEmbdedDocument)
				return $this->_embded->add($name, $value);
		}
		else
			parent::__set($name, $value);
	}

	public function afterValidate()
	{
		if($this->hasEmbdedDocuments())
			foreach($this->_embded as $doc)
			{
				if(!$doc->validate())
				{
					$this->addErrors($doc->getErrors());
				}
			}
	}

	public function embdedDocuments()
	{
		return array();
	}

	public function hasEmbdedDocuments()
	{
		//return $this->_embded
		return count($this->embdedDocuments()) > 0;
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
		if(!isset(self::$_names[$className]))
		{
			$class=new ReflectionClass(get_class($this));
			$names=array();
			foreach($class->getProperties() as $property)
			{
				$name=$property->getName();
				if($property->isPublic() && !$property->isStatic())
					$names[]=$name;
			}
			if($this->hasEmbdedDocuments())
			{
				$names = array_merge($names, $this->_embded->getKeys());
			}
			return self::$_names[$className]=$names;
		}
		else
			return self::$_names[$className];
	}

	public function toArray()
	{
		$arr = array();
		foreach($this as $key=>$value)
			$arr[$key]=$value;
		if($this->hasEmbdedDocuments())
			foreach($this->_embded as $key=>$value)
				$arr[$key]=$value->toArray();
		return $arr;
	}
}