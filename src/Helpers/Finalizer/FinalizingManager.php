<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Helpers\Finalizer;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Gazebo\PluginFactory;
use Maslosoft\Mangan\Interfaces\ArrayFinalizerInterface;
use Maslosoft\Mangan\Interfaces\ModelFinalizerInterface;
use Maslosoft\Mangan\Mangan;

/**
 * FinalizingManager
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class FinalizingManager
{

	public static function fromModel($data, $transformerClass, AnnotatedInterface $model)
	{
		$plugins = PluginFactory::fly()->instance(Mangan::fromModel($model)->finalizers, $transformerClass, ModelFinalizerInterface::class);
		foreach ($plugins as $finalizer)
		{
			/* @var $finalizer ModelFinalizerInterface */
			$finalizer->fromModel($data);
		}
		return $data;
	}

	public static function toModel($transformerClass, AnnotatedInterface $model)
	{
		$plugins = PluginFactory::fly()->instance(Mangan::fromModel($model)->finalizers, $transformerClass, ModelFinalizerInterface::class);
		foreach ($plugins as $finalizer)
		{
			/* @var $finalizer ArrayFinalizerInterface */
			$finalizer->toModel($model);
		}
		return $model;
	}

}
