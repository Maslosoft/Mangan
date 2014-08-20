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

	public $class = '';
	public $field = '_id';
	public $updatable = false;
	public $isArray = false;

}
