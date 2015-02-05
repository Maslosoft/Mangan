<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Mangan\Transformers;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ITransformator
{

	/**
	 * Returns the given object as an associative array
	 * @param IModel|object $model
	 * @param bool $withClassName Whenever to include special _class field
	 * @return array an associative array of the contents of this object
	 */
	public static function fromModel($model, $withClassName = true);

	/**
	 * Create document from array
	 * TODO Enforce $className if collection is homogenous
	 * @return object
	 */
	public static function toModel($data, $className = null);
}
