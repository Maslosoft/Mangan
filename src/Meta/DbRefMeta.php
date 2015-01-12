<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Meta;

/**
 * DbRef metadata holder
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DbRefMeta extends BaseMeta
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
	 * Default class for referenced document
	 * @var string
	 */
	public $class = '';

	/**
	 * Whenever referenced objects should be updated on save of main document
	 * @var bool
	 */
	public $updatable = false;

}
