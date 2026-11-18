<?php

declare(strict_types=1);

// Generate a development-only manifest without changing the distributable lockfile.
$root = dirname(__DIR__);
$path = realpath($argv[1] ?? $root . '/../embedi');
if ($path === false || !is_file($path . '/composer.json')) {
    throw new RuntimeException('Pass the path to the local EmbeDi checkout.');
}
$package = json_decode(file_get_contents($path . '/composer.json'), true, flags: JSON_THROW_ON_ERROR);
if (($package['name'] ?? null) !== 'maslosoft/embedi') {
    throw new RuntimeException('The selected checkout is not maslosoft/embedi.');
}
$config = json_decode(file_get_contents($root . '/composer.json'), true, flags: JSON_THROW_ON_ERROR);
$config['repositories'] = array_merge([[
    'type' => 'path',
    'url' => $path,
    'options' => [
        'symlink' => true,
        'versions' => ['maslosoft/embedi' => '3.0.0'],
    ],
]], $config['repositories'] ?? []);
file_put_contents($root . '/composer.local.json', json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n");
copy($root . '/composer.lock', $root . '/composer.local.lock');
echo "Generated composer.local.json and seeded composer.local.lock.\n";
echo "Run: COMPOSER=composer.local.json composer update maslosoft/embedi --with-all-dependencies\n";
