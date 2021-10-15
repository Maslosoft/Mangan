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
	 * @param AnnotatedInterface|null $model
	 * @param string                  $aspect
	 * @see AspectsInterface
	 */
	public static function addAspect(AnnotatedInterface $model = null, string $aspect = ''): void
	{
		if($model === null)
		{
			return;
		}
		if(empty($aspect))
		{
			return;
		}
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
	 * @param AnnotatedInterface|null $model
	 * @param string                  $aspect
	 * @see AspectsInterface
	 */
	public static function removeAspect(AnnotatedInterface $model = null, string $aspect = ''): void
	{
		if($model === null)
		{
			return;
		}
		if(empty($aspect))
		{
			return;
		}
		if($model instanceof AspectsInterface)
		{
			$model->removeAspect($aspect);
		}
	}

	/**
	 * Gracefully check if it has aspect, this will check
	 * if model implements `AspectsInterface`
	 * and check only if it does. If model does not
	 * implement AspectsInterface it will return `false`,
	 * like if it has no such aspect.
	 *
	 * @param AnnotatedInterface|null $model
	 * @param string                  $aspect
	 * @return bool
	 * @see AspectsInterface
	 */
	public static function hasAspect(AnnotatedInterface $model = null, string $aspect = ''): bool
	{
		if($model === null)
		{
			return false;
		}
		if(empty($aspect))
		{
			return false;
		}
		if($model instanceof AspectsInterface)
		{
			return $model->hasAspect($aspect);
		}
		return false;
	}
}