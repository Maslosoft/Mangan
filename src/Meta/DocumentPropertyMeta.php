<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Meta;

use Maslosoft\Addendum\Collections\MetaProperty;
use Maslosoft\Mangan\Sanitizers\ISanitizer;

/**
 * DocumentPropertyMeta
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DocumentPropertyMeta extends MetaProperty
{

	/**
	 * Field label
	 * @var string
	 */
	public $label = '';

	/**
	 * Description
	 * @var string
	 */
	public $description = '';

	/**
	 * DB Ref metadata
	 * @var DbRefMeta
	 */
	public $dbRef = null;

	/**
	 * Embedded document default class
	 * @var string|bool
	 */
	public $embedded = null;

	/**
	 * I18N metadata
	 * @var I18NMeta
	 */
	public $i18n = null;

	/**
	 * Decorators
	 * @var string[]
	 */
	public $decorators = [];

	/**
	 * Sanitizer
	 * @var ISanitizer
	 */
	public $sanitizer = null;

	/**
	 * If field should be persistent, by default true
	 * @var bool
	 */
	public $persistent = true;

	/**
	 * Whenever property is read only
	 * @var bool
	 */
	public $readonly = false;
}
