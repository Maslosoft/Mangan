<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Mangan\Interfaces\IActiveDocument;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * EmbeddedDocument
 *
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @author Piotr Maselkowski, Maslosoft
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @copyright 2013 Maslosoft http://maslosoft.com
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
	 * @Ignored
	 * @var ManganMeta
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

		$this->meta = ManganMeta::create($this);

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

	/**
	 * This ensures that embedded documents are also validated
	 * TODO Move to validator class
	 * @since v1.0.8
	 */
//	public function afterValidate()
//	{
//		if ($this->hasEmbeddedDocuments())
//		{
//			foreach ($this->meta->properties('embedded') as $field => $className)
//			{
//				if ($this->meta->$field->embeddedArray)
//				{
//					foreach ((array) $this->$field as $doc)
//					{
//						if ($doc instanceof EmbeddedDocument)
//						{
//							if (!$doc->validate())
//							{
//								$this->addErrors($doc->getErrors());
//							}
//						}
//					}
//				}
//				else
//				{
//					if ($this->$field instanceof EmbeddedDocument)
//					{
//						if (!$this->$field->validate())
//						{
//							$this->addErrors($this->$field->getErrors());
//						}
//					}
//				}
//			}
//		}
//	}

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
	 * @return string[] list of attribute names. Defaults to all public properties of the class.
	 * @since v1.0.8
	 */
	public function attributeNames()
	{
		return array_keys($this->meta->fields());
	}

	/**
	 * Attribute labels
	 * @return string[]
	 */
	public function attributeLabels()
	{
		return $this->meta->properties('label');
	}

// </editor-fold>

}
