<?php
/**
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @license New BSD license
 * @version 1.3
 * @category ext
 * @package ext.YiiMongoDbSuite
 */

/**
 * MongocrudGenerator.php
 *
 * @since		v1.2.2
 */

Yii::setPathOfAlias('mongoExtRoot', realpath(implode(DIRECTORY_SEPARATOR, array(
	dirname(__FILE__), '..', '..',
))));

/**
 * @since v1.2.2
 */
class MongoCRUDGenerator extends CCodeGenerator
{
	public $codeModel = 'mongoExtRoot.gii.mongoCRUD.MongoCRUDCode';
}