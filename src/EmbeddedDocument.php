<?php

/**
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @author Piotr Maselkowski, Maslosoft
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @copyright 2013 Maslosoft http://maslosoft.com
 * @license New BSD license
 * @version 2.0.1
 * @category ext
 * @package maslosoft/yii-mangan
 */

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Mangan\Core\Component;
use Maslosoft\Mangan\Events\ClassNotFound;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Interfaces\IActiveDocument;
use Maslosoft\Mangan\Model\Model;
use Yii;

/**
 * EmbeddedDocument
 *
 * @since v1.0.8
 * @property Meta $meta Model metadata
 */
abstract class EmbeddedDocument implements IActiveDocument
{

	use \Maslosoft\Mangan\Traits\I18NAbleTrait,
	  \Maslosoft\Mangan\Traits\OwneredTrait,
	  \Maslosoft\Mangan\Traits\ScenariosTrait,
	  \Maslosoft\Mangan\Traits\ValidatableTrait;

	/**
	 * This holds key for document order
	 * @SafeValidator
	 * @var string
	 */
	public $_key = '';

	/**
	 * This holds type of this embedded document
	 * @SafeValidator
	 * @var string
	 */
	public $_class = null;

	/**
	 * Model metadata
	 * @Persistent(false)
	 * @var Meta
	 */
	public $meta = null;

	/**
	 * Constructor.
	 * @param string $scenario name of the scenario that this model is used in.
	 * See {@link Model::scenario} on how scenario is used by models.
	 * @see getScenario
	 * @since v1.0.8
	 */
	public function __construct($scenario = 'insert', $lang = '')
	{
		$this->_class = get_class($this);

		$this->meta = Meta::create($this);

		$this->setLang($lang);
		$this->setScenario($scenario);
		$this->init();
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

// <editor-fold defaultstate="collapsed" desc="Events">
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

	public function onClassNotFound($event)
	{
		$this->raiseEvent(__FUNCTION__, $event);
	}

	/**
	 * @since v1.0.8
	 */
	protected function beforeToArray()
	{
		$event = new ModelEvent($this);
		$this->onBeforeToArray($event);
		return $event->isValid;
	}

	/**
	 * @since v1.0.8
	 */
	protected function afterToArray()
	{
		$this->onAfterToArray(new ModelEvent($this));
	}

	/**
	 * @since v1.0.8
	 */
	protected function beforeEmbeddedDocsInit()
	{
		$event = new ModelEvent($this);
		$this->onBeforeEmbeddedDocsInit($event);
		return $event->isValid;
	}

	/**
	 * @since v1.0.8
	 */
	protected function afterEmbeddedDocsInit()
	{
		$this->onAfterEmbeddedDocsInit(new ModelEvent());
	}

	/**
	 * Embedded class not found event handling
	 * @param string $className
	 * @return string
	 */
	protected function classNotfound($className)
	{
		$event = new ClassNotFound();
		$event->notFound = $className;
		$this->onClassNotFound($event);
		return $event->replacement;
	}

	/**
	 * This ensures that embedded documents are also validated
	 * @since v1.0.8
	 */
	public function afterValidate()
	{
		if ($this->hasEmbeddedDocuments())
		{
			foreach ($this->meta->properties('embedded') as $field => $className)
			{
				if ($this->meta->$field->embeddedArray)
				{
					foreach ((array) $this->$field as $doc)
					{
						if ($doc instanceof EmbeddedDocument)
						{
							if (!$doc->validate())
							{
								$this->addErrors($doc->getErrors());
							}
						}
					}
				}
				else
				{
					if ($this->$field instanceof EmbeddedDocument)
					{
						if (!$this->$field->validate())
						{
							$this->addErrors($this->$field->getErrors());
						}
					}
				}
			}
		}
	}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Default model implementation">
	/**
	 * Validation rules based on validator annotations
	 * @return mixed[][]
	 */
	public function rules()
	{
		$pattern = '~Validator$~';
		$result = [];
		foreach ($this->meta->fields() as $field => $meta)
		{
			foreach ($meta as $type => $value)
			{
				if (preg_match($pattern, $type))
				{
					$type = preg_replace($pattern, '', $type);
					$value = (array) $value;
					if (isset($value['class']))
					{
						$type = $value['class'];
						unset($value['class']);
					}
					$result[] = array_merge([$field, $type], $value);
				}
			}
		}
		return $result;
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
		if (!isset(self::$_attributes[$className]))
		{
			return self::$_attributes[$className] = array_keys((array) $this->meta->fields());
		}
		else
		{
			return self::$_attributes[$className];
		}
	}

	public function attributeLabels()
	{
		$labels = [];
		foreach ($this->meta->properties('label') as $key => $label)
		{
			$labels[$key] = Yii::t('', $label);
		}
		return $labels;
	}

// </editor-fold>

}
