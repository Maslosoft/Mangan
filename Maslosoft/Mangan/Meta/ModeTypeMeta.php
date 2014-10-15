<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Meta;

/**
 * Model meta container
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModeTypeMeta
{

	use \Maslosoft\Mangan\Traits\Defaults\MongoClientOptions;

	/**
	 * Whenever to use cursors
	 * @var bool
	 */
	public $useCursor = false;

}
