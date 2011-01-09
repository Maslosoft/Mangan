<?php
/**
 * bootstrap.php
 *
 * PHP version 5.2+
 *
 * @author		Dariusz Górecki <darek.krk@gmail.com>
 * @author		Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright	2010 CleverIT http://www.cleverit.com.pl
 * @license		http://www.yiiframework.com/license/ BSD license
 * @version		1.3
 * @category	ext
 * @package		ext.YiiMongoDbSuite
 *
 */

$appDir=dirname(__FILE__).'/../../..';

$yiit=$appDir.'/vendor/yii/framework/yiit.php';
$config=$appDir.'/config/test.php';

require_once($yiit);

Yii::setPathOfAlias('testDir', dirname(__FILE__));
Yii::import('testDir.unit.*');
Yii::import('testDir.unit.testModels.*');

Yii::createWebApplication($config);
