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

namespace Maslosoft\Mangan\Traits;

use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Helpers\CompositionIterator;
use Maslosoft\Mangan\Interfaces\InternationalInterface;
use Maslosoft\Mangan\Meta\ManganMeta;

/**
 * I18N-able trait contains basic implementation of I18N feature. This covers
 * methods from `InternationalInterface`.
 *
 * Use this trait to provide internationalized columns for models.
 *
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
	 * Get current working language code
	 * @return string Language code
	 * @Ignored
	 */
	public function getLang()
	{
		return $this->_lang? : $this->getDefaultLanguage();
	}

	/**
	 * Get available languages
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
	 *
	 * For initial call, when there are no data set yet, `$triggetEvents`
	 * can be set to false to improve performance.
	 *
	 * @param string $code
	 * @param boolean $triggerEvents
	 * @Ignored
	 */
	public function setLang($code, $triggerEvents = true)
	{
		// Ensure that calling with empty code will not set language
		// to empty value
		if(empty($code))
		{
			return false;
		}
		if ($this->_lang === $code)
		{
			return false;
		}
		if (!in_array($code, $this->getLanguages()))
		{
			$this->_languages[] = $code;
		}
		if(!$triggerEvents)
		{
			$this->_lang = $code;
			return true;
		}

		$it = new CompositionIterator($this);
		// Need a deep scan, as some objects
		// might have proxy documents that do not
		// implement InternationalInterface::class,
		// so those would be omitted. The setLang will
		// anyway not trigger if language is same as
		// currently set.
		$it->ofType(InternationalInterface::class);
		foreach($it as $model)
		{
			/* @var $model InternationalInterface */
			$model->setLang($code);
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
	 *
	 * Example valid array and method calls:
	 *
	 * ```php
	 * $languages = ['en', 'pl', 'ru'];
	 * $model->setLanguages($languages);
	 * $model2->setLanguages(['en']);
	 * ```
	 *
	 * For initial call, when there are no data set yet, `$triggetEvents`
	 * can be set to false to improve performance.
	 *
	 * @param string[] $languages
	 * @param boolean $triggerEvents
	 * @Ignored
	 */
	public function setLanguages($languages, $triggerEvents = true)
	{
		if($triggerEvents)
		{
			$event = new ModelEvent($this);
			$event->data = $languages;
			if (!Event::valid($this, InternationalInterface::EventBeforeLanguagesSet, $event))
			{
				return;
			}
		}
		$this->_languages = $languages;
		if($triggerEvents)
		{
			Event::trigger($this, InternationalInterface::EventAfterLanguagesSet, $event);
		}
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
	 * Get default language used for I18N operations.
	 *
	 * If not previously set, will fall back to `en`.
	 *
	 * @return string
	 * @Ignored
	 */
	public function getDefaultLanguage()
	{
		return $this->_defaultLanguage? : 'en';
	}

	/**
	 * Set default language used for I18N operations. This language
	 * will be used if the `setLang` method was not called.
	 *
	 * The value should be language code, for example `en`
	 *
	 * @param string $language
	 * @Ignored
	 */
	public function setDefaultLanguage($language)
	{
		$this->_defaultLanguage = $language;
	}

	/**
	 * Change i18n attributes values to appropriate language
	 * @param string $fromCode
	 * @param string $toCode
	 */
	private function _changeAttributesLang($fromCode, $toCode)
	{
		$meta = ManganMeta::create($this);
		$fields = $meta->properties('i18n');
		$defaultLang = $this->getDefaultLanguage();
		foreach ($fields as $name => $i18n)
		{
			$current = $this->$name;
			if (isset($this->_rawI18N[$name]) && array_key_exists($toCode, $this->_rawI18N[$name]))
			{
				$new = $this->_rawI18N[$name][$toCode];
			}
			else
			{
				$i18n = $meta->field($name)->i18n;

				if($i18n->allowDefault && isset($this->_rawI18N[$name]) && array_key_exists($defaultLang, $this->_rawI18N[$name]))
				{
					$new = $this->_rawI18N[$name][$defaultLang];
				}
				elseif($i18n->allowAny && !empty($this->_rawI18N[$name]))
				{
					$wasFound = false;
					foreach($this->getLanguages() as $code)
					{
						if(!empty($this->_rawI18N[$name][$code]))
						{
							$new = $this->_rawI18N[$name][$code];
							$wasFound = true;
							break;
						}
					}
					if(!$wasFound)
					{
						$new = $meta->$name->default;
					}
				}
				else
				{
					$new = $meta->$name->default;
				}
			}
			$this->_rawI18N[$name][$fromCode] = $current;
			$this->$name = $new;
		}
	}

}
