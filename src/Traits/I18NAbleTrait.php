<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Traits;

/**
 * I18NableTrait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait I18NAbleTrait
{

	private $_lang = 'en';
	private $_rawI18N = [];
	private $_languages = ['en'];

	public function getLang()
	{
		return $this->_lang;
	}

	public function getLanguages()
	{
		return $this->_languages;
	}

	public function getRawI18N()
	{
		return $this->_rawI18N;
	}

	public function setLang($code)
	{
		$this->_lang = $code;
	}

	public function setLangauges($languages)
	{
		$this->_languages = $languages;
	}

	public function setRawI18N($values)
	{
		$this->_rawI18N = $values;
	}

}
