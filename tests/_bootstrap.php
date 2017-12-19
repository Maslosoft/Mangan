<?php

use Maslosoft\Addendum\Addendum;
use Maslosoft\Mangan\Annotations\MetaOptionsHelper;
use Maslosoft\Mangan\Annotations\Validators\ValidatorAnnotation;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Validators\Proxy\RequiredProxy;
use Maslosoft\ManganTest\Models\ValidatorProxy\RequiredValidator;

date_default_timezone_set('Europe/Paris');

define('VENDOR_DIR', __DIR__ . '/../vendor');
define('YII_DIR', VENDOR_DIR . '/yiisoft/yii/framework/');
require VENDOR_DIR . '/autoload.php';

// Invoker stub for windows
if (defined('PHP_WINDOWS_VERSION_MAJOR'))
{
	require __DIR__ . '/../misc/Invoker.php';
}

$config = require __DIR__ . '/config.php';

$addendum = new Addendum();
$addendum->namespaces[] = MetaOptionsHelper::Ns;
$addendum->namespaces[] = ValidatorAnnotation::Ns;
$addendum->init();

		const ManganFirstDbName = 'ManganTest';
		const ManganSecondDbName = 'ManganTestSecond';
		const ManganThirdDbName = 'ManganTestThird';
		const ManganCustomValidatorsDbName = 'ManganTestCustomValidators';

$mangan = new Mangan();
$mangan->connectionString = 'mongodb://localhost:27017';
$mangan->dbName = ManganFirstDbName;
$mangan->init();

$mangan2 = new Mangan('second');
$mangan2->connectionString = 'mongodb://localhost:27017';
$mangan2->dbName = ManganSecondDbName;
$mangan2->init();

$mangan3 = new Mangan('tokumx');
$mangan3->connectionString = 'mongodb://localhost:27017';
$mangan3->dbName = ManganThirdDbName;
$mangan3->init();

$mangan4 = new Mangan('custom-validators');
$mangan4->connectionString = 'mongodb://localhost:27017';
$mangan4->dbName = ManganCustomValidatorsDbName;
$mangan4->validators[RequiredProxy::class] = RequiredValidator::class;
$mangan4->init();
