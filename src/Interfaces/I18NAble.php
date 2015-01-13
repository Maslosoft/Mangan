<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

/**
 * Implement this interface on your model to anable I18N fields support
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface I18NAble
{

	const EventBeforeLangChange = 'beforeLangChange';
	const EventAfterLangChange = 'afterLangChange';

	/**
	 * Get language code
	 * @return string Language code
	 */
	public function getLang();

	/**
	 * Set language code
	 * @param string $code
	 */
	public function setLang($code);

	/**
	 * Get i18n values with all languages.
	 * This method must return value set by `setRawI18N`
	 * @return mixed[] Associative array of language values
	 */
	public function getRawI18N();

	/**
	 * Set i18n values in all languages.
	 * This method must keep `$values` for further use, by method `getRawI18N`.
	 * @param mixed[] $values
	 */
	public function setRawI18N($values);

	/**
	 * This method must return available language codes
	 * @deprecated since version number
	 * @return string[] Array of language codes
	 */
	public function getLanguages();

	/**
	 * Set all available language codes
	 * @deprecated since version number
	 * @param string[] $languages
	 */
	public function setLangauges($languages);

	/**
	 * Get default language code
	 */
	public function getDefaultLanguage();

	/**
	 * Set default language code
	 * @param string $code
	 */
	public function setDefaultLanguage($code);
}
