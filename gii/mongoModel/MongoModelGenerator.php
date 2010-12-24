<?php
Yii::setPathOfAlias('mongoModel', dirname(__FILE__));
Yii::import('application.components.mongo.*');
Yii::import('application.components.mongo.sample.base.*');
Yii::import('application.components.mongo.sample.*');
class MongoModelGenerator extends CCodeGenerator
{
	public $codeModel='mongoModel.MongoCodeModel';
}