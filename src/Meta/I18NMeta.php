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

namespace Maslosoft\Mangan\Meta;

use Maslosoft\Mangan\Decorators\I18NDecorator;
use Maslosoft\Mangan\Helpers\PropertyMaker;

/**
 * I18NMeta
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class I18NMeta
{

	use \Maslosoft\Mangan\Traits\Access\GetSet;

	public $enabled = false;
	public $allowDefault = false;
	public $allowAny = false;
	private $_enabled = false;

	/**
	 * Parent metadata
	 * @var DocumentPropertyMeta
	 */
	private $_meta = null;

	public function __construct(DocumentPropertyMeta $meta)
	{
		PropertyMaker::defineProperty($this, 'enabled');
		$this->_meta = $meta;
	}

	public function getEnabled()
	{
		return $this->_enabled;
	}

	public function setEnabled($enabled)
	{
		if ($enabled)
		{
			$this->_enabled = true;
			$this->_meta->decorators[] = I18NDecorator::class;
		}
		else
		{
			$this->_enabled = true;
			$key = array_search(I18NDecorator::class, $this->_meta->decorators);
			unset($this->_meta->decorators[$key]);
		}
	}

}
