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

namespace Maslosoft\Mangan\Meta;

use Maslosoft\Addendum\Collections\MetaType;
use Maslosoft\Mangan\Annotations\AliasAnnotation;
use Maslosoft\Mangan\Annotations\ClientFlagAnnotation;
use Maslosoft\Mangan\Annotations\CollectionNameAnnotation;
use Maslosoft\Mangan\Annotations\ConnectionIdAnnotation;
use Maslosoft\Mangan\Annotations\EntityManagerAnnotation;
use Maslosoft\Mangan\Annotations\FinderAnnotation;
use Maslosoft\Mangan\Annotations\HomogenousAnnotation;
use Maslosoft\Mangan\Annotations\LabelAnnotation;
use Maslosoft\Mangan\Annotations\PrimaryKeyAnnotation;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Exceptions\ManganException;
use Maslosoft\Mangan\Helpers\PropertyMaker;
use Maslosoft\Mangan\Interfaces\ScopeInterface;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Traits\Access\GetSet;
use Maslosoft\Mangan\Traits\Defaults\MongoClientOptions;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

/**
 * Model meta container
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DocumentTypeMeta extends MetaType
{

	use MongoClientOptions,
	  GetSet;

	/**
	 * Field label
	 * @see LabelAnnotation
	 * @var string
	 */
	public $label = '';

	/**
	 * Description
	 * @var string
	 */
	public $description = '';

	/**
	 * Collection name
	 * @see CollectionNameAnnotation
	 * @var string
	 */
	public $collectionName = '';

	/**
	 * Connection ID
	 * @see ConnectionIdAnnotation
	 * @var string
	 */
	public $connectionId = Mangan::DefaultConnectionId;

	/**
	 * Annotation defined client connection flags
	 * @see ClientFlagAnnotation
	 * @var mixed[]
	 */
	public $clientFlags = [];

	/**
	 * Primary key field or fields
	 * @see PrimaryKeyAnnotation
	 * @var string|array
	 */
	public $primaryKey = null;

	/**
	 * Whenever to use cursors
	 * @var bool
	 */
	public $useCursor = false;

	/**
	 * Whenever colleciton is homogenous
	 * @see HomogenousAnnotation
	 * @var bool
	 */
	public $homogenous = true;

	/**
	 * Finder class name to return by Finder from create method
	 * @see Finder::create()
	 * @see FinderAnnotation
	 * @var string
	 */
	public $finder = null;

	/**
	 * Entity Manager class name to return by EntityManager from create method
	 * @see EntityManager::create()
	 * @see EntityManagerAnnotation
	 * @var string
	 */
	public $entityManager = null;

	/**
	 * Property aliases. This consists of source property name as key, and destination property as value.
	 * @var string[]
	 * @see AliasAnnotation
	 */
	public $aliases = [];

	/**
	 * Validators configuration
	 * @var ValidatorMeta[]
	 */
	public $validators = [];


	/**
	 * @see ScopeInterface
	 * @var array
	 */
	public $scopes = [];

	/**
	 * Values of properties
	 * @var mixed
	 */
	private $_values = [];
	private $_defaults = [];

	public function __construct(ReflectionClass $info = null)
	{
		// Client Options must be unset to allow cascading int EntityOptions
		parent::__construct($info);
		foreach ($this->_getOptionNames() as $name)
		{
			PropertyMaker::defineProperty($this, $name, $this->_values);
			$this->_defaults[$name] = true;
		}
	}

	public function __get($name)
	{
		if ($this->_hasGetter($name))
		{
			return parent::__get($name);
		}
		if (!array_key_exists($name, $this->_values))
		{
			throw new ManganException(sprintf('Trying to read unitialized property `%s`', $name));
		}
		return $this->_values[$name];
	}

	public function __set($name, $value)
	{
		if ($this->_hasSetter($name))
		{
			return parent::__set($name);
		}
		$this->_values[$name] = $value;
		$this->_defaults[$name] = false;
		return null;
	}

	public function __isset($name)
	{
		return array_key_exists($name, $this->_defaults) && $this->_defaults[$name] === false;
	}

}
