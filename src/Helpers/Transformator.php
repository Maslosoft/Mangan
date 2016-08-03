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

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Exceptions\TransformatorException;
use Maslosoft\Mangan\Interfaces\NameAwareInterface;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * Transformator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class Transformator
{

	/**
	 * Metadata for document
	 * @var ManganMeta
	 */
	private $meta = null;

	/**
	 * Hash map of sanitizers
	 * @var object[]
	 */
	private $transformators = [];

	/**
	 * Model
	 * @var object
	 */
	private $model = null;

	/**
	 * Transormator class name
	 * @var string
	 */
	private $transformatorClass = TransformatorInterface::class;

	/**
	 * Class constructor
	 * @param AnnotatedInterface $model
	 * @param string $transformatorClass
	 */
	public function __construct(AnnotatedInterface $model, $transformatorClass = TransformatorInterface::class)
	{
		$this->meta = ManganMeta::create($model);
		$this->transformatorClass = $transformatorClass;
		$this->model = $model;
	}

	/**
	 * Get transformator class
	 * @return string
	 */
	public function getTransformatorClass()
	{
		return $this->transformatorClass;
	}

	/**
	 * Get model instance
	 * @return AnnotatedInterface
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * Get Mangan meta data
	 * @return ManganMeta
	 */
	public function getMeta()
	{
		return $this->meta;
	}

	/**
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function getFor($name)
	{
		if (!array_key_exists($name, $this->transformators))
		{
			if (!$this->meta->$name)
			{
				throw new TransformatorException(sprintf('There is not metadata for field `%s` of model `%s`, have you declared this field?', $name, get_class($this->getModel())));
			}
			$this->transformators[$name] = $this->_getTransformer($this->transformatorClass, $this->meta->type(), $this->meta->$name);
		}

		// Support for setting name in sanitizers etc.
		if ($this->transformators[$name] instanceof NameAwareInterface)
		{
			$this->transformators[$name]->setName($name);
		}
		return $this->transformators[$name];
	}

	public function __get($name)
	{
		return $this->getList($name);
	}

	public function __set($name, $value)
	{
		throw new TransformatorException(sprintf('Cannot set field `%s` of `%s` (tried to set with value of type `%s`)', $name, __CLASS__, gettype($value)));
	}

	/**
	 * Get transformer
	 * @param string $transformatorClass
	 * @param DocumentTypeMeta $modelMeta
	 * @param DocumentPropertyMeta $fieldMeta
	 * @return object
	 */
	abstract protected function _getTransformer($transformatorClass, DocumentTypeMeta $modelMeta, DocumentPropertyMeta $fieldMeta);
}
