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
use Maslosoft\Mangan\Interfaces\InternationalInterface;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * I18NableTrait
 * @see InternationalInterface
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
	 * @Ignored
	 */
	public function getLang()
	{
		return $this->_lang? : $this->getDefaultLanguage();
	}

	/**
	 *
	 * @return string[]
	 * @Ignored
	 */
	public function getLanguages()
	{
		return $this->_languages;
	}

	/**
	 * Get i18n values with all languages.
	 * This returns all language values of all class fields. Class field names are
	 * keys for arrays of language values, with language codes as a keys.
	 *
	 * Example returned variable:
	 * ```php
	 * [
	 * 		'name' => [
	 * 			'en' => 'January',
	 * 			'pl' => 'Styczeń'
	 * 		],
	 * 		'description' => [
	 * 			'en' => 'First mothn of a year'
	 * 			'pl' => 'Pierwszy miesiąc roku'
	 * 		]
	 * ]
	 * ```
	 * @return mixed[] Associative array of language values
	 * @Ignored
	 */
	public function getRawI18N()
	{
		$meta = ManganMeta::create($this);
		$fields = $meta->properties('i18n');

		// Set current model value for current language for each property
		foreach ($fields as $name => $i18n)
		{
			// This is to keep rawi18n value in sync with current model value.
			$this->_rawI18N[$name][$this->getLang()] = $this->$name;
		}
		return $this->_rawI18N;
	}

	/**
	 * Set language code. This changes current model language.
	 * After setting language model attributes will store values in different locale.
	 *
	 * Language code must be previously set by `setLanguages`.
	 * When trying to set undefined language code, method will do nothing.
	 * When setting already choosen language code, method will also ignore this call.
	 * Example method calls:
	 * ```php
	 * // Set available languages
	 * $model->setLanguages(['en', 'pl']);
	 *
	 * // Will ignore as en is by default
	 * $model->setLang('en');
	 *
	 * // Will set pl as language
	 * $model->setLang('pl');
	 *
	 * // Will ignore as ru is not available
	 * $model->setLang('ru')
	 * ```
	 * @param string $code
	 * @Ignored
	 */
	public function setLang($code)
	{
		if ($this->_lang === $code)
		{
			return false;
		}
		if (!in_array($code, $this->getLanguages()))
		{
			$this->_languages[] = $code;
		}
		$event = new ModelEvent($this);
		$event->data = $code;
		if (!Event::valid($this, InternationalInterface::EventBeforeLangChange, $event))
		{
			return false;
		}
		$this->_changeAttributesLang($this->_lang, $code);
		$this->_lang = $code;
		Event::trigger($this, InternationalInterface::EventAfterLangChange, $event);
		return true;
	}

	/**
	 * Set available languages. This method accepts one parameter,
	 * array contaning language codes prefably in short ISO form.
	 * Example valid array and method calls:
	 * ```php
	 * $languages = ['en', 'pl', 'ru'];
	 * $model->setLanguages($languages);
	 * $model2->setLanguages(['en']);
	 * ```
	 * @param string[] $languages
	 * @Ignored
	 */
	public function setLanguages($languages)
	{
		$event = new ModelEvent($this);
		$event->data = $languages;
		if (!Event::valid($this, InternationalInterface::EventBeforeLanguagesSet, $event))
		{
			return;
		}
		$this->_languages = $languages;
		Event::trigger($this, InternationalInterface::EventAfterLanguagesSet, $event);
	}

	/**
	 * Set i18n values in all languages.
	 * This method must keep `$values` for further use, by method `getRawI18N`.
	 * @param mixed[] $values
	 * @Ignored
	 */
	public function setRawI18N($values)
	{
		$this->_rawI18N = $values;
	}

	/**
	 *
	 * @return string
	 * @Ignored
	 */
	public function getDefaultLanguage()
	{
		return $this->_defaultLanguage? : 'en';
	}

	/**
	 *
	 * @param string $language
	 * @Ignored
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
