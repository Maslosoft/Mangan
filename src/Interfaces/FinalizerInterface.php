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

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Mangan\Transformers\Transformer;

/**
 * Finalizer is kind of decorator, which do something with resulting data from `Transformer`.
 * This can either apply additional formatting, convert to other format,
 * serialize or even log what is converted to and from model.
 *
 * @see Transformer
 * @see ArrayFinalizerInterface
 * @see ModelFinalizerInterface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface FinalizerInterface extends ArrayFinalizerInterface, ModelFinalizerInterface
{

}
