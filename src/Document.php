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
use MongoCursor;
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
	 * @Sanitizer('MongoObjectId')
	 * @see setId()
	 * @see getId()
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
	 * Add scopes functionality.
	 * @since v1.0
	 */
//	public function __call($name, $parameters)
//	{
//		$scopes = $this->scopes();
//		if (isset($scopes[$name]))
//		{
//			$this->getDbCriteria()->mergeWith($scopes[$name]);
//			return $this;
//		}
//	}

	/**
	 * Constructor {@see setScenario()}.
	 * @param string $scenario
	 * @since v1.0
	 */
	public function __construct($scenario = 'insert', $lang = '')
	{
		$this->_key = (string) new MongoId();
		$this->_class = get_class($this);
		$this->meta = ManganMeta::create($this);
		$this->setLang($lang);

		// internally used by populateRecord() and model()
		if ($scenario == null)
		{
			return;
		}

		$this->setScenario($scenario);
		$this->init();
	}

	/**
	 * Returns the declaration of named scopes.
	 * A named scope represents a query criteria that can be chained together with
	 * other named scopes and applied to a query. This method should be overridden
	 * by child classes to declare named scopes for the particular document classes.
	 * For example, the following code declares two named scopes: 'recently' and
	 * 'published'.
	 * <pre>
	 * return array(
	 * 	'published'=>array(
	 * 		'conditions'=>array(
	 * 				'status'=>array('==', 1),
	 * 		),
	 * 	),
	 * 	'recently'=>array(
	 * 		'sort'=>array('create_time'=>Criteria::SORT_DESC),
	 * 		'limit'=>5,
	 * 	),
	 * );
	 * </pre>
	 * If the above scopes are declared in a 'Post' model, we can perform the following
	 * queries:
	 * <pre>
	 * $posts=Post::model()->published()->findAll();
	 * $posts=Post::model()->published()->recently()->findAll();
	 * $posts=Post::model()->published()->published()->recently()->find();
	 * </pre>
	 *
	 * @return array the scope definition. The array keys are scope names; the array
	 * values are the corresponding scope definitions. Each scope definition is represented
	 * as an array whose keys must be properties of {@link Criteria}.
	 * @since v1.0
	 */
//	public function scopes()
//	{
//		return [];
//	}

	/**
	 * Returns the default named scope that should be implicitly applied to all queries for this model.
	 * Note, default scope only applies to SELECT queries. It is ignored for INSERT, UPDATE and DELETE queries.
	 * The default implementation simply returns an empty array. You may override this method
	 * if the model needs to be queried with some default criteria (e.g. only active records should be returned).
	 * @return array the mongo criteria. This will be used as the parameter to the constructor
	 * of {@link Criteria}.
	 * @since v1.2.2
	 */
//	public function defaultScope()
//	{
//		return [];
//	}

	/**
	 * Resets all scopes and criteria applied including default scope.
	 *
	 * @return Document
	 * @since v1.0
	 */
//	public function resetScope()
//	{
//		$this->_criteria = new Criteria();
//		return $this;
//	}

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
