<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 23.01.18
 * Time: 13:47
 */

namespace Maslosoft\Mangan\Interfaces\Decorators;


use Maslosoft\Mangan\Transformers\CriteriaArray;

interface ConditionDecoratorTypeInterface
{
	public function setDecoratorType($type = CriteriaArray::class);
}