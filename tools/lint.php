<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$count = 0;
foreach (['src', 'tests', 'tools'] as $directory) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(
        $root . '/' . $directory,
        FilesystemIterator::SKIP_DOTS,
    ));
    foreach ($files as $file) {
        if (!$file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }
        $process = proc_open([PHP_BINARY, '-n', '-d', 'error_reporting=-1', '-l', $file->getPathname()], [1 => ['pipe', 'w'], 2 => ['redirect', 1]], $pipes);
        if ($process === false) {
            throw new RuntimeException('Unable to start the PHP linter.');
        }
        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        if (proc_close($process) !== 0 || !str_starts_with(trim($output), 'No syntax errors detected in ')) {
            fwrite(STDERR, $output);
            exit(1);
        }
        ++$count;
    }
}
echo "Syntax valid in {$count} PHP files.\n";
