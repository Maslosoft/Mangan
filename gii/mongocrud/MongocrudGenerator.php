<?php
/**
 * MongocrudGenerator.php
 *
 * PHP version 5.2+
 *
 * @author		Dariusz Górecki <darek.krk@gmail.com>
 * @copyright	2010 CleverIT
 * @license		http://www.yiiframework.com/license/ BSD license
 * @version		1.3
 * @category	ext
 * @package		ext.YiiMongoDbSuite
 *
 */

Yii::setPathOfAlias('mongoExtRoot', realpath(implode(DIRECTORY_SEPARATOR, array(
	dirname(__FILE__), '..', '..',
))));

class MongocrudGenerator extends CCodeGenerator
{
	public $codeModel = 'mongoExtRoot.gii.mongocrud.MongocrudCode';
}