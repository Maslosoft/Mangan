<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
