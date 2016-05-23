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

use Maslosoft\Mangan\Interfaces\ActiveRecordInterface;
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
abstract class Document extends EmbeddedDocument implements ActiveRecordInterface
{

	use \Maslosoft\Mangan\Traits\EntityManagerTrait,
	  \Maslosoft\Mangan\Traits\FinderTrait,
	  \Maslosoft\Mangan\Traits\CollectionNameTrait,
	  \Maslosoft\Mangan\Traits\WithCriteriaTrait;

	/**
	 * Mongo id field
	 * @Label('Database ID')
	 * @Sanitizer(MongoObjectId)
	 * @see MongoObjectId
	 * @var MongoId|null
	 */
	public $_id = null;

	/**
	 * Alias to _id
	 * @Label('Database ID')
	 * @Persistent(false)
	 * @Alias('_id')
	 * @see https://github.com/Maslosoft/Mangan/issues/40
	 * @var string|null
	 */
	public $id = null;

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
		$this->_id = new MongoId;
		$this->_key = (string) new MongoId();
		$this->_class = get_class($this);
		$this->setLang($lang);

		$this->setScenario($scenario);
		$this->init();
	}

	/**
	 * Returns the empty model of the specified Document class.
	 * It is provided for invoking class-level methods, espacially userfull for finders.
	 *
	 * Example usage:
	 * ```php
	 * $user = User::model()->findByPk('5612470866a19540308b4568');
	 * ```
	 * @param string $lang
	 * @return Document model instance.
	 */
	public static function model($lang = null)
	{
		return new static(null, $lang);
	}

}
