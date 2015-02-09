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

namespace Maslosoft\Mangan\Helpers;

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Mangan\Exceptions\TransformatorException;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Sanitizers\ISanitizer;
use Maslosoft\Mangan\Transformers\ITransformator;

/**
 * Transformator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class Transformator
{

	/**
	 * Metadata for document
	 * @var Meta
	 */
	private $_meta = null;

	/**
	 * Hash map of sanitizers
	 * @var ISanitizer[]
	 */
	private $_transformators = [];

	/**
	 * Model
	 * @var object
	 */
	private $_model = null;

	/**
	 * Transormator class name
	 * @var string
	 */
	private $_transformatorClass = ITransformator::class;

	public function __construct($document, $transformatorClass = ITransformator::class)
	{
		$this->_meta = ManganMeta::create($document);
		$this->_transformatorClass = $transformatorClass;
		$this->_model = $document;
	}

	public function getModel()
	{
		return $this->_model;
	}

	/**
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function getFor($name)
	{
		if (!array_key_exists($name, $this->_transformators))
		{
			if(!$this->_meta->$name)
			{
				throw new TransformatorException(sprintf('There is not metadata for field `%s` of model `%s`, have you declared this field?', $name, get_class($this->getModel())));
			}
			$this->_transformators[$name] = $this->_getTransformer($this->_transformatorClass, $this->_meta->type(), $this->_meta->$name);
		}
		return $this->_transformators[$name];
	}

	public function __get($name)
	{
		return $this->getList($name);
	}

	public function __set($name, $value)
	{
		throw new TransformatorException(sprintf('Cannot set field `%s` of `%s` (tried to set with value of type `%s`)', $name, __CLASS__, gettype($value)));
	}

	abstract protected function _getTransformer($transformatorClass, DocumentTypeMeta $documentMeta,  DocumentPropertyMeta $fieldMeta);
}
