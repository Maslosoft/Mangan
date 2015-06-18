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

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
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
	private $_defaultLanguage = 'en';

	/**
	 * Get language code
	 * @return string Language code
	 * @Ignore
	 */
	public function getLang()
	{
		return $this->_lang? : $this->getDefaultLanguage();
	}

	/**
	 *
	 * @return string[]
	 * @Ignore
	 */
	public function getLanguages()
	{
		return $this->_languages;
	}

	/**
	 * Get i18n values with all languages.
	 * This method must return value set by `setRawI18N`
	 * @return mixed[] Associative array of language values
	 * @Ignore
	 */
	public function getRawI18N()
	{
		$meta = ManganMeta::create($this);
		$fields = $meta->properties('i18n');
		foreach ($fields as $name => $i18n)
		{
			$this->_rawI18N[$name][$this->getLang()] = $this->$name;
		}
		return $this->_rawI18N;
	}

	/**
	 * Set language code
	 * @param string $code
	 * @Ignore
	 */
	public function setLang($code)
	{
		if ($this->_lang === $code)
		{
			return;
		}
		if (!in_array($code, $this->getLanguages()))
		{
			return;
		}
		$event = new ModelEvent($this);
		$event->data = $code;
		if (!Event::valid($this, I18NAble::EventBeforeLangChange, $event))
		{
			return;
		}
		$this->_changeAttributesLang($this->_lang, $code);
		$this->_lang = $code;
		Event::trigger($this, I18NAble::EventAfterLangChange, $event);
	}

	/**
	 *
	 * @param type $languages
	 * @return void
	 * @Ignore
	 */
	public function setLanguages($languages)
	{
		$event = new ModelEvent($this);
		$event->data = $languages;
		if (!Event::valid($this, I18NAble::EventBeforeLanguagesSet, $event))
		{
			return;
		}
		$this->_languages = $languages;
		Event::trigger($this, I18NAble::EventAfterLanguagesSet, $event);
	}

	/**
	 * Set i18n values in all languages.
	 * This method must keep `$values` for further use, by method `getRawI18N`.
	 * @param mixed[] $values
	 * @Ignore
	 */
	public function setRawI18N($values)
	{
		$this->_rawI18N = $values;
	}

	/**
	 *
	 * @return string
	 * @Ignore
	 */
	public function getDefaultLanguage()
	{
		return $this->_defaultLanguage? : 'en';
	}

	/**
	 *
	 * @param string $language
	 * @Ignore
	 */
	public function setDefaultLanguage($language)
	{
		$this->_defaultLanguage = $language;
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
