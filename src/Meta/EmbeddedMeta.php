<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Meta;

/**
 * Embedded metadata holder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmbeddedMeta extends BaseMeta
{

	/**
	 * Whenever treat field as single referenced document
	 * @var bool
	 */
	public $single = false;

	/**
	 * Whenever field should contain array of referenced documents.
	 * @var bool
	 */
	public $isArray = false;

	/**
	 * Comparing key. This is used to update db ref instances from external sources.
	 * This is only usefull in embedded arrays.
	 * @var string|array
	 */
	public $key = null;

	/**
	 * Default class for embedded documents, or doucment arrays.
	 * @var string
	 */
	public $class = null;

}
