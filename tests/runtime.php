<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Maslosoft\Addendum\Addendum;
use Maslosoft\EmbeDi\EmbeDi;
use Maslosoft\Light\Context;
use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Helpers\IndexManager;
use Maslosoft\Mangan\Helpers\Sanitizer\Factory;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Meta\DocumentPropertyMeta;
use Maslosoft\Mangan\Meta\DocumentTypeMeta;
use Maslosoft\Mangan\Sanitizers\StringSanitizer;
use Maslosoft\Mangan\Transaction;
use Maslosoft\Mangan\Transformers\RawArray;
use MongoDB\Client;

error_reporting(E_ALL);
set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    if ($severity === E_DEPRECATED && str_starts_with($file, dirname(__DIR__) . '/vendor/')) {
        return false;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

function checkRuntime(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

class RuntimeMangan extends Mangan {}

final class RuntimePrefixSanitizer extends StringSanitizer
{
    public string $prefix = '';

    public function read($model, $dbValue)
    {
        return $this->prefix . parent::read($model, $dbValue);
    }
}

function sanitizerFor(string $prefix): object
{
    $mangan = Mangan::fly();
    $mangan->sanitizersMap[RawArray::class][StringSanitizer::class] = [
        'class' => RuntimePrefixSanitizer::class,
        'prefix' => $prefix,
    ];
    $field = new DocumentPropertyMeta();
    $field->name = 'value';
    $field->default = '';
    $model = new DocumentTypeMeta();
    $model->name = 'RuntimeModel';
    return Factory::create($field, $model, RawArray::class);
}

// Addendum metadata is process-shared; configure it before starting requests.
Addendum::fly();
Context::put('unrelated', 'preserved');
$first = new Mangan();
checkRuntime($first === Mangan::fly(), 'Direct construction must register the default instance.');
checkRuntime($first !== Mangan::fly('second'), 'Connection IDs must be independent.');
checkRuntime(RuntimeMangan::fly() instanceof RuntimeMangan, 'Subclass flyweights must preserve their class.');
checkRuntime(RuntimeMangan::fly() !== $first, 'Subclass flyweights must not alias Mangan.');
$first->dbName = 'first';
$first->init();
$client = $first->getConnection();
$database = $first->getDbInstance();
$first->setConnection(new Client());
checkRuntime($first->getDbInstance() !== $database, 'Replacing the client must invalidate the database handle.');
$path = IndexManager::fly()->getStoragePath();
$first->selectDb('second');
checkRuntime($path !== IndexManager::fly()->getStoragePath(), 'Index cache paths must include selected database.');
$path = IndexManager::fly()->getStoragePath();
$first->setConnection(new Client('mongodb://127.0.0.1:27019'));
checkRuntime($path !== IndexManager::fly()->getStoragePath(), 'Index cache paths must include an injected client target.');
Event::on(stdClass::class, 'runtime', static function (): void {});
checkRuntime(Event::trigger(stdClass::class, 'runtime'), 'Class-level events must not require a model instance.');
checkRuntime(Event::hasHandler(stdClass::class, 'runtime'), 'Handlers must be visible in their context.');
$sanitizer = sanitizerFor('first:');
checkRuntime($sanitizer->read(new stdClass(), 'value') === 'first:value', 'Sanitizer configuration must be applied.');
Mangan::reset();
checkRuntime(Context::get('unrelated') === 'preserved', 'Mangan reset must preserve unrelated Light entries.');
$second = Mangan::fly();
checkRuntime($first !== $second, 'Sequential jobs must receive a fresh Mangan instance after reset.');
checkRuntime($second->getConnection() !== $client, 'Connection handles must not survive Mangan reset.');
checkRuntime(!Event::hasHandler(stdClass::class, 'runtime'), 'Handlers must not survive Mangan reset.');
checkRuntime(sanitizerFor('second:')->read(new stdClass(), 'value') === 'second:value', 'Sanitizer caches must not leak between jobs.');
checkRuntime(Transaction::getRunningSession() === null, 'An empty transaction stack must return null.');
checkRuntime($second->dbName === 'first', 'Mangan reset must not destroy separately owned EmbeDi configuration.');
Mangan::reset();
EmbeDi::reset();

$run = function_exists('Swoole\\Coroutine\\run') ? 'Swoole\\Coroutine\\run'
    : (function_exists('OpenSwoole\\Coroutine\\run') ? 'OpenSwoole\\Coroutine\\run' : null);
if ($run === null) {
    echo "SKIP: Native coroutine isolation requires Swoole or OpenSwoole.\n";
} else {
    $coroutine = str_starts_with($run, 'OpenSwoole') ? OpenSwoole\Coroutine::class : Swoole\Coroutine::class;
    $instances = [];
    $run(static function () use ($coroutine, &$instances): void {
        for ($i = 0; $i < 2; ++$i) {
            $coroutine::create(static function () use ($coroutine, &$instances, $i): void {
                $mangan = Mangan::fly();
                checkRuntime(Event::hasHandler(\Maslosoft\Addendum\Interfaces\AnnotatedInterface::class, \Maslosoft\Mangan\Interfaces\FinderInterface::EventBeforeFind), 'Default handlers must initialize in each coroutine.');
                $mangan->dbName = 'tenant_' . $i;
                $mangan->init();
                $instances[$i] = $mangan;
                Event::on(stdClass::class, 'tenant_' . $i, static function (): void {});
                $sanitizer = sanitizerFor('tenant_' . $i . ':');
                $coroutine::sleep(0.001);
                checkRuntime(Mangan::fly() === $mangan, 'A suspended coroutine must retain its Mangan instance.');
                checkRuntime((new Mangan())->dbName === 'tenant_' . $i, 'EmbeDi snapshots must follow coroutine scope.');
                checkRuntime(!Event::hasHandler(stdClass::class, 'tenant_' . (1 - $i)), 'Events must be isolated between sibling coroutines.');
                checkRuntime($sanitizer->read(new stdClass(), 'value') === 'tenant_' . $i . ':value', 'Configured sanitizer instances must be isolated.');
            });
        }
    });
    checkRuntime($instances[0] !== $instances[1], 'Sibling coroutines must have distinct Mangan instances.');
    checkRuntime(Mangan::fly()->dbName === null, 'Coroutine configuration must not leak back into the worker.');
    echo "Native coroutine isolation passed.\n";
}
Context::clear();
echo "Runtime isolation regressions passed.\n";
