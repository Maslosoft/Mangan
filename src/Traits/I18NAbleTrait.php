<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Interfaces\I18NAble;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * I18NableTrait
 * @see I18NAble
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait I18NAbleTrait
{

	private $_lang = 'en';
	private $_rawI18N = [];
	private $_languages = ['en'];

	/**
	 * Get language code
	 * @return string Language code
	 */
	public function getLang()
	{
		return $this->_lang;
	}

	public function getLanguages()
	{
		return $this->_languages;
	}

	/**
	 * Get i18n values with all languages.
	 * This method must return value set by `setRawI18N`
	 * @return mixed[] Associative array of language values
	 */
	public function getRawI18N()
	{
		return $this->_rawI18N;
	}

	/**
	 * Set language code
	 * @param string $code
	 */
	public function setLang($code)
	{
		if ($this->_lang === $code)
		{
			return;
		}
		if (!Event::valid($this, I18NAble::EventBeforeLangChange))
		{
			return;
		}
		$this->_changeAttributesLang($this->_lang, $code);
		$this->_lang = $code;
		Event::trigger($this, I18NAble::EventAfterLangChange);
	}

	public function setLangauges($languages)
	{
		$this->_languages = $languages;
	}

	/**
	 * Set i18n values in all languages.
	 * This method must keep `$values` for further use, by method `getRawI18N`.
	 * @param mixed[] $values
	 */
	public function setRawI18N($values)
	{
		$this->_rawI18N = $values;
	}

	/**
	 * Change i18n attributes values to apropriate language
	 * @param string $fromCode
	 * @param string $toCode
	 */
	private function _changeAttributesLang($fromCode, $toCode)
	{
		$meta = ManganMeta::create($this);
		$fields = $meta->properties('i18n');
		foreach ($fields as $name => $i18n)
		{
			$current = $this->$name;
			if (isset($this->_rawI18N[$name]) && array_key_exists($toCode, $this->_rawI18N[$name]))
			{
				$new = $this->_rawI18N[$name][$toCode];
			}
			else
			{
				$new = $meta->$name->default;
			}
			$this->_rawI18N[$name][$fromCode] = $current;
			$this->$name = $new;
		}
	}

}
