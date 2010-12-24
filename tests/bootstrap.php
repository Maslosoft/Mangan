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

$yiit = '/home/canni/workspace/lib/yii/framework/yiit.php';
require $yiit;
Yii::setPathOfAlias('YiiMongoDbSuite', dirname(__FILE__).'/..');
Yii::import('YiiMongoDbSuite.*');

$testAppDir = '/tmp/Yii_testTmpApp';

if(!file_exists($testAppDir))
{
	@mkdir($testAppDir, 0777);
	exec('echo y | '.realpath(dirname($yiit).'/yiic').' webapp '.$testAppDir, $output, $return);
}

$app = Yii::createConsoleApplication($testAppDir.'/protected/config/console.php');

$mongoDB = Yii::createComponent(array(
	'class'=>'EMongoDB',
	'connectionString'=>'mongodb://localhost',
	'dbName'=>'EMongoDbSuiteTest',
	'fsyncFlag'=>false
));

Yii::app()->setComponent('mongodb', $mongoDB);