<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan;

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Mangan\Interfaces\ActiveDocumentInterface;
use Maslosoft\Mangan\Interfaces\InitInterface;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use Maslosoft\Mangan\Traits\I18NAbleTrait;
use Maslosoft\Mangan\Traits\OwneredTrait;
use Maslosoft\Mangan\Traits\ScenariosTrait;
use Maslosoft\Mangan\Traits\ValidatableTrait;
use MongoId;

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
abstract class EmbeddedDocument implements ActiveDocumentInterface, InitInterface
{

	use I18NAbleTrait,
	  OwneredTrait,
	  ScenariosTrait,
	  ValidatableTrait;

	/**
	 * Mongo id field
	 * NOTE: This is usefull for embedded documents too, as it is used for keeping order
	 * @Label('Database ID')
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId|null
	 */
	public $_id = null;

	/**
	 * This holds key for document order
	 * TODO Is this even nessesary?
	 * @deprecated This is unused
	 * @SafeValidator
	 * @var string
	 */
	public $_key = '';

	/**
	 * This holds type of this embedded document.
	 * While this field is not required, it is usefull in some scenarios,
	 * for example to generate JavaScript model classes from PHP classes.
	 * @SafeValidator
	 * @var string
	 */
	public $_class = '';

	/**
	 * Constructor.
	 * @param string $scenario name of the scenario that this model is used in.
	 * See {@link Model::scenario} on how scenario is used by models.
	 * @see getScenario
	 * @since v1.0.8
	 */
	public function __construct($scenario = 'insert', $lang = '')
	{
		$this->_id = new MongoId;

		$this->_class = static::class;

		$this->setLang($lang);
		$this->setScenario($scenario);
		$this->init();
	}

	/**
	 * Initializes this model.
	 * This method is invoked in the constructor right after {@link scenario} is set.
	 * You may override this method to provide code that is needed to initialize the model (e.g. setting
	 * initial property values.)
	 * @deprecated since version number
	 * @since 1.0.8
	 */
	public function init()
	{

	}

}
