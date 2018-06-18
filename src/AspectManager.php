<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 18.06.18
 * Time: 21:59
 */

namespace Maslosoft\Mangan;


use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Mangan\Interfaces\AspectsInterface;

class AspectManager
{
	/**
	 * Gracefully add aspect, this will check
	 * if model implements AspectsInterface
	 * and add only if it does.
	 *
	 * @see AspectsInterface
	 * @param AnnotatedInterface $model
	 * @param                    $aspect
	 */
	public static function addAspect(AnnotatedInterface $model, $aspect)
	{
		if($model instanceof AspectsInterface)
		{
			$model->addAspect($aspect);
		}
	}

	/**
	 * Gracefully remove aspect, this will check
	 * if model implements AspectsInterface
	 * and remove only if it does.
	 *
	 * @see AspectsInterface
	 * @param AnnotatedInterface $model
	 * @param                    $aspect
	 */
	public static function removeAspect(AnnotatedInterface $model, $aspect)
	{
		if($model instanceof AspectsInterface)
		{
			$model->removeAspect($aspect);
		}
	}

	/**
	 * Gracefully check if has aspect, this will check
	 * if model implements `AspectsInterface`
	 * and check only if it does. If model does not
	 * implement AspectsInterface it will return `false`,
	 * like if it has no such aspect.
	 *
	 * @see AspectsInterface
	 * @param AnnotatedInterface $model
	 * @param                    $aspect
	 * @return bool|string
	 */
	public static function hasAspect(AnnotatedInterface $model, $aspect)
	{
		if($model instanceof AspectsInterface)
		{
			return $model->hasAspect($aspect);
		}
		return false;
	}
}