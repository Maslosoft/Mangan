<?php

use Maslosoft\Mangan\Command;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Tools\AvailableCommandsGenerator;
use Maslosoft\Mangan\Transaction;
use Maslosoft\ManganTest\Models\BaseAttributesAnnotations;

// Here you can initialize variables that will be available to your tests
error_reporting(E_ALL);

echo "Mangan: " . (new Mangan())->getVersion() . PHP_EOL;
echo "MongoDB: " . (new Command())->buildInfo()['version'] . PHP_EOL;
$transactions = 'false';
$t = (new Transaction(new BaseAttributesAnnotations));
$t->commit();
if ($t->isAvailable())
{
	$transactions = 'true';
}
(new AvailableCommandsGenerator)->generate();
echo "Transactions: " . $transactions . PHP_EOL;

foreach(['mongodb', 'second', 'tokumx', 'custom-validators'] as $connectionId)
{
	echo "Using DB: " . Mangan::fly($connectionId)->dbName . PHP_EOL;
}
ini_set('xdebug.max_nesting_level', 200);
