<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
