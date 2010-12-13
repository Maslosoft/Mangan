<?php

Yii::setPathOfAlias('mongoExtRoot', realpath(implode(DIRECTORY_SEPARATOR, array(
	dirname(__FILE__), '..', '..',
))));

class MongocrudGenerator extends CCodeGenerator
{
	public $codeModel = 'mongoExtRoot.gii.mongocrud.MongocrudCode';
}