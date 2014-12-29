<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Meta;

/**
 * Embedded document metadata
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmbeddedMeta
{

	/**
	 * Whenever treat field as single embedded document
	 * @var bool
	 */
	public $single = false;

	/**
	 * Whenever field should contain array of embedded documents.
	 * @var bool
	 */
	public $isArray = false;

	/**
	 * Default class for embedded document
	 * @var string
	 */
	public $class = '';

	/**
	 * Addidtional embedded params
	 * @var mixed
	 */
	public $params = null;

}
