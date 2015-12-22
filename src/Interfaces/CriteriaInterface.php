<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Interfaces;

use Maslosoft\Mangan\Interfaces\Criteria\CursorAwareInterface;
use Maslosoft\Mangan\Interfaces\Criteria\DecoratableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\LimitableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\MergeableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\SelectableInterface;
use Maslosoft\Mangan\Interfaces\Criteria\SortableInterface;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface CriteriaInterface extends CursorAwareInterface, DecoratableInterface, LimitableInterface, MergeableInterface, SelectableInterface, SortableInterface
{

}
