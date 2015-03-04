<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Transformers;

use Maslosoft\Mangan\EntityManager;

/**
 * This transformer is configured to set only safe attributes - from any external source.
 * @see EntityManager::setAttributes()
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SafeArray extends Transformer implements ITransformator
{

}
