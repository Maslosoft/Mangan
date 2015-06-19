<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan;

use Maslosoft\Mangan\Interfaces\IActiveRecord;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Sanitizers\MongoObjectId;
use MongoDB;
use MongoId;

/**
 * Document
 *
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @property-read MongoDB $db
 * @since v1.0
 */
abstract class Document extends EmbeddedDocument implements IActiveRecord
{

	use \Maslosoft\Mangan\Traits\EntityManagerTrait,
	  \Maslosoft\Mangan\Traits\FinderTrait,
	  \Maslosoft\Mangan\Traits\CollectionNameTrait,
	  \Maslosoft\Mangan\Traits\WithCriteriaTrait;

	/**
	 * Mongo id field
	 * @KoBindable(false)
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId|mixed
	 */
	public $_id;

	/**
	 * Alias to _id
	 * @Label('Database ID')
	 * @Persistent(false)
	 * @see setId()
	 * @see getId()
	 * @var string
	 */
	public $id;

	/**
	 * Constructor
	 * @see ScenarioManager
	 *
	 * @param string $scenario
	 * @param string $lang Language code
	 * @since v1.0
	 */
	public function __construct($scenario = 'insert', $lang = '')
	{
		$this->_key = (string) new MongoId();
		$this->_class = get_class($this);
		$this->meta = ManganMeta::create($this);
		$this->setLang($lang);

		$this->setScenario($scenario);
		$this->init();
	}

	/**
	 * Returns the static model of the specified Document class.
	 * The model returned is a static instance of the Document class.
	 * It is provided for invoking class-level methods (something similar to static class methods.)
	 * @param string $lang
	 * @return Document model instance.
	 */
	public static function model($lang = null)
	{
		$className = get_called_class();
		return new $className(null, $lang);
	}

}
