<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Transformers;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\AspectManager;
use Maslosoft\Mangan\Interfaces\Transformators\TransformatorInterface;

/**
 * This transformer is by default configured to store document in database.
 * 
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RawArray extends Transformer implements TransformatorInterface
{

}
