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
 * EMongoEmbeddedDocument
 *
 * @since v1.0.8
 */
abstract class EMongoEmbeddedDocument extends CModel implements IAnnotated
{
	/**
	 * This holds type of this embedded document
	 * @todo When creating embedded document instance from mongo data,
	 * check this variable and instantiate properly, by creating array of config
	 * with stored values, and array field 'class' with this value, and than call
	 * Yii::createComponent
	 * @var string
	 */
	public $_class = null;

	/**
	 * Hold down owner pointer (if any)
	 *
	 * @var EMongoEmbeddedDocument $_owner
	 * @since v1.0.8
	 */
	protected $_owner = null;

	/**
	 * @todo Check if it is nessesary
	 * @var type
	 */
	private static $_attributes = array();

	/**
	 * Model metadata
	 * @Persistent(false)
	 * @var MModelMeta
	 */
	private static $_meta = [];

	/**
	 * Current document language
	 * @var string
	 */
	private $_lang = '';

	/**
	 * Array with raw i18n attributes (with all language versions)
	 * @Persistent(false)
	 * @var mixed[]
	 */
	public $rawI18N = null;

	/**
	 * Array with all not directly accessed fields values
	 * @var mixed[]
	 */
	private $_virtualValues = [];

	/**
	 * Constructor.
	 * @param string $scenario name of the scenario that this model is used in.
	 * See {@link CModel::scenario} on how scenario is used by models.
	 * @see getScenario
	 * @since v1.0.8
	 */
	public function __construct($scenario = 'insert', $lang = '')
	{
		$this->_class = get_class($this);
		$this->meta->initModel($this);
		$this->setLang($lang);
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
	public function init()
	{

	}

	/**
	 * @since v1.0.8
	 */
	protected function initEmbeddedDocuments()
	{
		if(!$this->hasEmbeddedDocuments() || !$this->beforeEmbeddedDocsInit())
			return false;

		$this->afterEmbeddedDocsInit();
	}

	/**
	 * @since v1.0.8
	 */
	public function onBeforeEmbeddedDocsInit($event)
	{
		$this->raiseEvent('onBeforeEmbeddedDocsInit', $event);
	}

	/**
	 * @since v1.0.8
	 */
	public function onAfterEmbeddedDocsInit($event)
	{
		$this->raiseEvent('onAfterEmbeddedDocsInit', $event);
	}

	/**
	 * @since v1.0.8
	 */
	public function onBeforeToArray($event)
	{
		$this->raiseEvent('onBeforeToArray', $event);
	}

	/**
	 * @since v1.0.8
	 */
	public function onAfterToArray($event)
	{
		$this->raiseEvent('onAfterToArray', $event);
	}

	/**
	 * @since v1.0.8
	 */
	protected function beforeToArray()
	{
		$event = new CModelEvent($this);
		$this->onBeforeToArray($event);
		return $event->isValid;
	}

	/**
	 * @since v1.0.8
	 */
	protected function afterToArray()
	{
		$this->onAfterToArray(new CModelEvent($this));
	}

	/**
	 * @since v1.0.8
	 */
	protected function beforeEmbeddedDocsInit()
	{
		$event = new CModelEvent($this);
		$this->onBeforeEmbeddedDocsInit($event);
		return $event->isValid;
	}

	/**
	 * @since v1.0.8
	 */
	protected function afterEmbeddedDocsInit()
	{
		$this->onAfterEmbeddedDocsInit(new CModelEvent());
	}

	/**
	 * Support for get accessors for fields
	 * Also dot notation is supported for embedded documents, which can be used
	 * while getting fields with variable variables
	 * @example Fieldname: testField; get method: getTestField;
	 * @param string $name
	 * @return mixed result of get<fieldName> function
	 */
	public function __get($name)
	{
		if(strstr($name, '.'))
		{
			$parts = explode('.', $name, 2);
			return $this->{$parts[0]}->{$parts[1]};
		}
		if($name == 'meta')
		{
			return $this->getMeta();
		}
		$meta = self::$_meta[$this->_class]->$name;
		if($meta)
		{
			if($meta->readonly)
			{
				return $this->getAttribute($name);
			}
			if($meta->callGet)
			{
				return $this->{$meta->methodGet}();
			}
			if($meta->i18n)
			{
				return $this->getAttribute($name);
			}
			if($meta->embedded)
			{
				return $this->getAttribute($name);
			}
		}
		return parent::__get($name);
	}

	/**
	 * Support for set accessors for fields
	 * Also dot notation is supported for embedded documents, which can be used
	 * while getting fields with variable variables
	 * @example Fieldname: testField; set method: setTestField;
	 * @param string $name
	 * @param mixed $value
	 * @return mixed result of get<fieldName> function
	 */
	public function __set($name, $value)
	{
		if(strstr($name, '.'))
		{
			$parts = explode('.', $name, 2);
			$this->{$parts[0]}->{$parts[1]} = $value;
			return $this->{$parts[0]}->{$parts[1]} = $value;
		}
		$meta = self::$_meta[$this->_class]->$name;
		if($meta)
		{
			if($meta->readonly)
			{
				return '';
			}
			if($meta->callSet)
			{
				return $this->{$meta->methodSet}($value);
			}
			if($meta->embedded)
			{
				return $this->setAttribute($name, $value);
			}
			if($meta->i18n)
			{
				return $this->setAttribute($name, $value);
			}
		}
		return parent::__set($name, $value);
	}

	/**
	 * @since v1.3.2
	 * @see CComponent::__isset()
	 */
	public function __isset($name)
	{
		if($this->meta->$name->embedded)
		{
			return isset($this->_virtualValues[$name]);
		}
		else
			return parent::__isset($name);
	}

	/**
	 * @since v1.0.8
	 */
	public function afterValidate()
	{
		if($this->hasEmbeddedDocuments())
			foreach($this->meta->properties('embedded') as $field => $className)
			{
				if($this->meta->$field->embeddedArray)
				{
					foreach($this->$field as $doc)
					{
						if($doc instanceof EMongoEmbeddedDocument)
						{
							if(!$doc->validate())
							{
								$this->addErrors($doc->getErrors());
							}
						}
					}
				}
				else
				{
					if($this->$field instanceof EMongoEmbeddedDocument)
					{
						if(!$this->$field->validate())
						{
							$this->addErrors($this->$field->getErrors());
						}
					}
				}
			}
	}

	/**
	 * @todo Detect if we deal with built-in validator or user validator
	 * FIXME Add support for user defined validators, currently disabled
	 * name, consider this in converting utility! @see CValidator::builtInValidators
	 * Each build in validator annotation should implement IBuiltInValidatorAnnotation to distinguish it from other validators
	 * @return CValidator[]
	 */
	public function rules()
	{
		$result = [];
		foreach($this->meta->fields() as $field => $meta)
		{
			foreach($meta as $type => $value)
			{
				if($value instanceof IBuiltInValidatorAnnotation)
				{
					$type = preg_replace('~Validator$~', '', $type);
					$result[] = array_merge([$field, $type], $value->toArray());
				}
				elseif($value instanceof EValidatorAnnotation)
				{
					// TODO
				}
			}
		}
		return $result;
	}

	/**
	 * @since v1.0.8
	 */
	public function embeddedDocuments()
	{
		return $this->meta->properties('embedded');
	}

	/**
	 * @since v1.0.8
	 */
	public function hasEmbeddedDocuments()
	{
		return count($this->embeddedDocuments()) > 0;
	}

	/**
	 * Returns the list of attribute names.
	 * By default, this method returns all public properties of the class.
	 * You may override this method to change the default.
	 * @return array list of attribute names. Defaults to all public properties of the class.
	 * @since v1.0.8
	 */
	public function attributeNames()
	{
		if(!isset(self::$_attributes[$className]))
		{
			return self::$_attributes[$className] = array_keys((array)$this->meta->fields());
		}
		else
		{
			return self::$_attributes[$className];
		}
	}

	public function attributeLabels()
	{
		return $this->meta->properties('label');
	}

	/**
	 * Returns the given object as an associative array
	 * Fires beforeToArray and afterToArray events
	 * @return array an associative array of the contents of this object
	 * @since v1.0.8
	 */
	public function toArray()
	{
		if($this->beforeToArray())
		{
			$arr = $this->_toArray();
			$this->afterToArray();
			return $arr;
		}
		else
			return array();
	}

	/**
	 * This method does the actual convertion to an array
	 * Does not fire any events
	 * @return array an associative array of the contents of this object
	 * @since v1.3.4
	 */
	protected function _toArray()
	{
		$arr = [];
		foreach($this->meta->fields() as $name => $field)
		{
			// Type check is required here, so by default attribute is persistent
			if($field->persistent !== false)
			{
				if($field->i18n)
				{
					foreach(Yii::app()->languages as $lang => $langName)
					{
						if($field->embedded)
						{
							if($field->embeddedArray)
							{
								$value = [];
								foreach($this->getAttribute($name, $lang) as $docValue)
								{
									if(!$docValue instanceof EMongoEmbeddedDocument)
									{
										continue;
									}
									$value[] = $docValue->toArray();
								}
							}
							else
							{
								$value = $this->getAttribute($name, $lang);
								if($value instanceof EMongoEmbeddedDocument)
								{
									$value = $value->toArray();
								}
								else
								{
									$value = $field->default;
								}
							}
						}
						else
						{
							$value = $this->getAttribute($name, $lang);
						}
						$arr[$name][$lang] = $value;
					}
				}
				else
				{
					if($field->embedded)
					{
						if($field->embeddedArray)
						{
							$value = [];
							foreach($this->getAttribute($name) as $docValue)
							{
//								var_dump($name);
//								var_dump($docValue->toArray());
								$value[] = $docValue->toArray();
							}
						}
						else
						{
							$value = $this->getAttribute($name)->toArray();
						}
					}
					else
					{
						$value = $this->getAttribute($name);
					}
					$arr[$name] = $value;
				}
			}
		}
		$arr['_class'] = $this->_class;
		return $arr;
	}

	/**
	 * Get raw attribute, as is stored in db
	 * @param string $name
	 * @return mixed
	 */
	public function getAttribute($name, $lang = '')
	{
		$meta = self::$_meta[$this->_class]->$name;
		if(!$meta->direct)
		{
			if($meta->i18n)
			{
				if(!$lang)
				{
					$lang = $this->getLang();
				}
				$value = $this->_virtualValues[$name][$lang];
				if(null === $value && $meta->embedded)
				{
					$value = $this->_instantiateEmbedded($name, $value);
				}
				return $value;
			}
			else
			{
				if(!isset($this->_virtualValues[$name]))
				{
					$this->_virtualValues[$name] = $meta->default;
				}
				$value = $this->_virtualValues[$name];
				if(null === $value && $meta->embedded)
				{
					$value = $this->_instantiateEmbedded($name, $value);
				}
				return $value;
			}
		}
		else
		{
			return $this->$name;
		}
	}

	/**
	 * Set raw attribute
	 * @param string $name
	 * @param mixed $value
	 */
	public function setAttribute($name, $value, $lang = '')
	{
		$meta = self::$_meta[$this->_class]->$name;
		if(!$meta->direct)
		{
			if($meta->embedded)
			{
				if($meta->embeddedArray)
				{
					$docs = [];
					foreach((array)$value as $docValue)
					{
						$docs[] = $this->_instantiateEmbedded($name, $docValue);
					}
					$value = $docs;
				}
				else
				{
					$value = $this->_instantiateEmbedded($name, $value);
				}
			}
			if($meta->i18n)
			{
				if(!$lang)
				{
					$lang = $this->getLang();
				}
				$this->_virtualValues[$name][$lang] = $value;
			}
			else
			{
				$this->_virtualValues[$name] = $value;
			}
		}
		else
		{
			$this->$name = $value;
		}
	}

	/**
	 * Sets the attribute values in a massive way.
	 * @param array $values attribute values (name=>value) to be set.
	 * @param boolean $safeOnly whether the assignments should only be done to the safe attributes.
	 * A safe attribute is one that is associated with a validation rule in the current {@link scenario}.
	 * @see getSafeAttributeNames
	 * @see attributeNames
	 * @since v1.3.1
	 */
	public function setAttributes($values, $safeOnly = true)
	{
		if(!is_array($values))
			return;
		if($this->hasEmbeddedDocuments())
		{
			$attributes = array_flip($safeOnly ? $this->getSafeAttributeNames() : $this->attributeNames());

			foreach($this->embeddedDocuments() as $fieldName => $className)
			{
				if(isset($values[$fieldName]) && isset($attributes[$fieldName]))
				{
					// TODO Check if it's ok
//					$this->$fieldName->setAttributes($values[$fieldName], $safeOnly);
					$this->setAttribute($fieldName, $values[$fieldName]);
					unset($values[$fieldName]);
				}
			}
		}
		parent::setAttributes($values, $safeOnly);
	}

	/**
	 * Get current language code, defaults to Yii::app()->language
	 * @return string
	 */
	public function getLang()
	{
		if(!$this->_lang)
		{
			$this->_lang = Yii::app()->language;
		}
		return $this->_lang;
	}

	/**
	 * Set current language, but only if it is defined in application languages
	 * @param string $value language code
	 */
	public function setLang($value)
	{
		if(!$value)
		{
			$value = Yii::app()->language;
		}
		if(in_array($value, array_keys(Yii::app()->languages)))
		{
//			throw new Exception($value);
			$this->_lang = $value;
		}
	}

	public function getRawI18N()
	{
		$result = new stdClass();
		foreach($this->meta->fields() as $name => $field)
		{
			if($field->i18n)
			{
				$result->$name = new stdClass();
				foreach(Yii::app()->languages as $lang => $langName)
				{
					$value = $this->getAttribute($name, $lang);
					$result->$name->$lang = $value;
				}
			}
		}
		return $result;
	}

	/**
	 * Return owner of this document
	 * @return EMongoEmbeddedDocument
	 * @since v1.0.8
	 */
	public function getOwner()
	{
		if($this->_owner !== null)
			return $this->_owner;
		else
			return null;
	}

	/**
	 * Set owner of this document
	 * @param EMongoEmbeddedDocument $owner
	 * @since v1.0.8
	 */
	public function setOwner(EMongoEmbeddedDocument $owner)
	{
		$this->_owner = $owner;
	}

	public function getMeta()
	{
		if(!isset(self::$_meta[$this->_class]))
		{
			self::$_meta[$this->_class] = MModelMeta::create($this);
		}
		return self::$_meta[$this->_class];
	}

	/**
	 * Override default setScenario method for populating to embedded records
	 * @see CModel::setScenario()
	 * @todo Set scenario for embedded documents
	 * @since v1.0.8
	 */
	public function setScenario($value)
	{
//		if($this->hasEmbeddedDocuments() && $this->_embedded !== null)
//		{
//			foreach($this->_embedded as $doc)
//			{
//				$doc->setScenario($value);
//			}
//		}
		parent::setScenario($value);
	}

	/**
	 * Create instance of embedded document, based on defined type or
	 * __class field if it is set in data
	 * @param type $name
	 * @param type $value
	 */
	private function _instantiateEmbedded($name, $value = [])
	{
		if($value instanceof EMongoEmbeddedDocument)
		{
			return $value;
		}
		if(isset($value['_class']) && $value['_class'])
		{
			$docClassName = $value['_class'];
		}
		else
		{
			$docClassName = $this->meta->$name->embedded;
		}
		// This is for automatic doc type, and if its new instance
		// TODO Default class name should be configurable
		if($docClassName === true)
		{
			$docClassName = 'EMongoSoftDocument';
		}
		$doc = new $docClassName($this->getScenario(), $this->getLang());
		$doc->setOwner($this);
		if($value)
		{
			$doc->setAttributes($value);
		}
		return $doc;
	}
}
