<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/mangan
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
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
