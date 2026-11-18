<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Cli\Shared\Helpers\PhpExporter;
use Maslosoft\Mangan\File\Wrappers\StringWrapper;
use Maslosoft\Mangan\Helpers\CompositionIterator;
use Maslosoft\Mangan\Meta\ValidatorMeta;
use Maslosoft\Mangan\Sanitizers\DateSanitizer;
use Maslosoft\Mangan\Traits\Access\AsArray;
use Maslosoft\Mangan\Transformers\JsonString;
use Maslosoft\Mangan\Transformers\RawArray;
use Maslosoft\Mangan\Transformers\YamlString;
use MongoDB\BSON\UTCDateTime;
use Symfony\Component\Yaml\Exception\DumpException;
use Symfony\Component\Yaml\Exception\ParseException;

error_reporting(E_ALL);
set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    // Report upstream deprecations normally while failing on every project diagnostic.
    if ($severity === E_DEPRECATED && str_starts_with($file, dirname(__DIR__) . '/vendor/')) {
        return false;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

final class CompatibilityDocument implements AnnotatedInterface
{
    public array $array = [];
    public string $value = 'initial';
}

final class CompatibilityArrayAccess implements ArrayAccess
{
    use AsArray;

    public object $meta;
    public string $value = 'initial';

    public function __construct()
    {
        $this->meta = (object) ['value' => (object) ['default' => 'initial']];
    }
}

$checks = 0;
$check = static function (bool $condition, string $message) use (&$checks): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
    ++$checks;
};
$throws = static function (callable $callback, string $exception) use ($check): void {
    try {
        $callback();
    } catch (Throwable $error) {
        $check($error instanceof $exception, sprintf('Expected %s, got %s: %s', $exception, $error::class, $error->getMessage()));
        return;
    }
    throw new RuntimeException(sprintf('Expected %s to be thrown.', $exception));
};

$model = new CompatibilityDocument();
$date = new DateSanitizer();
$canonical = ['$date' => ['$numberLong' => '1735689600123']];
$check((string) $date->read($model, $canonical) === '1735689600123', 'Canonical BSON dates must retain milliseconds.');
$check((string) $date->write($model, ['sec' => 1735689600, 'usec' => 456789]) === '1735689600456', 'Legacy date microseconds must be converted to milliseconds.');
$check((string) $date->read($model, ['sec' => 0, 'usec' => 0]) === '0', 'Serialized epoch values must not become the current date.');
$check((string) $date->read($model, 1735689600.125) === '1735689600125', 'Fractional timestamps must work with ext-mongodb 2.');
$instant = new DateTimeImmutable('2025-01-01T00:00:00.789Z');
$check((string) $date->write($model, $instant) === '1735689600789', 'DateTimeInterface input must preserve milliseconds.');
$bsonDate = new UTCDateTime(1735689600123);
$check($date->read($model, $bsonDate) === $bsonDate, 'Existing BSON dates must pass through.');
$throws(static fn () => $date->read($model, ['invalid' => true]), InvalidArgumentException::class);

$model->array = ['one', 'two'];
ob_start();
try {
    $roundtrip = RawArray::toModel(RawArray::fromModel($model), CompatibilityDocument::class);
    $output = ob_get_contents();
} finally {
    ob_end_clean();
}
$check($output === '', 'Model transformations must not emit debug output for array fields.');
$check($roundtrip->array === $model->array, 'Array fields must survive raw transformations.');

$yaml = YamlString::fromModel($model);
$check(YamlString::toModel($yaml, CompatibilityDocument::class)->array === $model->array, 'YAML arrays must round trip.');
$throws(static fn () => YamlString::toModel('value: !php/object foo', CompatibilityDocument::class, null, true), ParseException::class);
$resource = fopen('php://memory', 'rb+');
try {
    $model->array = ['resource' => $resource];
    $throws(static fn () => YamlString::fromModel($model, [], 2, 4, true), DumpException::class);
} finally {
    fclose($resource);
}
$model->array = [];
$throws(static fn () => JsonString::toModel('{invalid', CompatibilityDocument::class), JsonException::class);
$check(JsonString::toModel(JsonString::fromModel($model), CompatibilityDocument::class)->value === 'initial', 'JSON must round trip.');

$access = new CompatibilityArrayAccess();
$check(isset($access['value']) && !isset($access[0]), 'ArrayAccess must recognize named fields.');
$access['value'] = 'changed';
$check($access['value'] === 'changed', 'ArrayAccess must write and read values.');
unset($access['value']);
$check($access['value'] === 'initial', 'ArrayAccess unset must restore the metadata default.');
$iterator = new CompositionIterator($model);
$iterator->rewind();
$check(count($iterator) === 0 && !$iterator->valid() && $iterator->current() === null, 'Empty composition iterators must be valid PHP iterators without undefined offsets.');

$validatorMeta = new ValidatorMeta([
    'class' => 'Application\\CustomValidator',
    'strict' => true,
    'applicationOption' => ['allowed' => ['one', 'two']],
    'nullableOption' => null,
]);
$check($validatorMeta->applicationOption === ['allowed' => ['one', 'two']], 'Custom validator metadata must accept arbitrary configuration options.');
$check(array_key_exists('nullableOption', (array) $validatorMeta) && $validatorMeta->nullableOption === null, 'Custom validator metadata must preserve explicit null options.');
$metadataCache = tempnam(sys_get_temp_dir(), 'mangan-validator-meta-');
try {
    file_put_contents($metadataCache, PhpExporter::export($validatorMeta));
    $cachedMeta = require $metadataCache;
    $check($cachedMeta instanceof ValidatorMeta && (array) $cachedMeta === (array) $validatorMeta, 'Emitted PHP metadata must restore arbitrary validator options without diagnostics.');
} finally {
    unlink($metadataCache);
}

$filename = tempnam(sys_get_temp_dir(), 'mangan-compatibility-');
try {
    foreach (['0', str_repeat('x', 8192) . '0', "\0binary\0", ''] as $bytes) {
        $wrapper = new StringWrapper($bytes, []);
        $check($wrapper->write($filename), 'Writing a file wrapper must succeed.');
        $check(file_get_contents($filename) === $bytes, 'File wrappers must preserve every byte, including trailing ASCII zero.');
    }
} finally {
    unlink($filename);
}

restore_error_handler();
echo sprintf("Compatibility checks passed (%d).\n", $checks);
