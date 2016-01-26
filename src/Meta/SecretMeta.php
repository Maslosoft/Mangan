<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Meta;

/**
 * SecretMeta
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SecretMeta extends BaseMeta
{

	/**
	 * Whether field is secret
	 * @var bool
	 */
	public $secret = true;

	/**
	 * Callback to process field if not empty
	 * @var callable|null
	 */
	public $callback = null;

}
