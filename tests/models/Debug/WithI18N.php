<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\ManganTest\Models\Debug;

use Exception;
use Maslosoft\Mangan\Document;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\Mangan\Sanitizers\PassThrough;

/**
 * WithI18N
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class WithI18N extends Document
{

	use \Maslosoft\Mangan\Traits\Model\TrashableTrait;

	/**
	 * Name
	 *
	 * @Label('Name')
	 * @Description('User friendly name')
	 * @RequiredValidator
	 * @Decorator({'Link', 'uac/userGroup/update', 'id'})
	 * @I18N(allowAny = true)
	 * @var string
	 */
	public $name = '';

	/**
	 * Raw i18n values.
	 * NOTE: This call getter.
	 * @Persistent(false)
	 * @Sanitizer(PassThrough)
	 * @see PassThrough
	 * @see getRawI18N
	 * @var string[][]
	 */
	public $rawI18N = [];

	public function __construct($scenario = 'insert', $lang = '')
	{
		parent::__construct($scenario, $lang);
		$this->setLang($lang);
		foreach (ManganMeta::create($this)->fields() as $name => $fieldMeta)
		{
			if ($fieldMeta->callGet || $fieldMeta->callSet || !$fieldMeta->direct)
			{
				unset($this->$name);
			}
		}
		unset($this->rawI18N);
	}

	public function __get($name)
	{
		$methodName = sprintf('get%s', ucfirst($name));
		if (method_exists($this, $methodName))
		{
			return $this->{$methodName}();
		}
		throw new Exception("Unknown attribute $name");
	}

	public function __set($name, $value)
	{
		$methodName = sprintf('set%s', ucfirst($name));
		if (method_exists($this, $methodName))
		{
			return $this->$methodName($value);
		}
		throw new Exception("Unknown attribute $name");
	}

	public function __toString()
	{
		return $this->name;
	}

}
