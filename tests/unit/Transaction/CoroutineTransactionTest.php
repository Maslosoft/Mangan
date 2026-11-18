<?php

namespace Transaction;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Transaction;
use Maslosoft\ManganTest\Models\TokuMX\ModelTransactional;
use RuntimeException;
use Throwable;

class CoroutineTransactionTest extends Unit
{
    public function testActiveSessionsAreIsolatedBetweenCoroutines(): void
    {
        $run = function_exists('Swoole\\Coroutine\\run') ? 'Swoole\\Coroutine\\run'
            : (function_exists('OpenSwoole\\Coroutine\\run') ? 'OpenSwoole\\Coroutine\\run' : null);
        if ($run === null) {
            $this->markTestSkipped('Native isolation requires Swoole or OpenSwoole.');
        }
        $coroutine = str_starts_with($run, 'OpenSwoole') ? \OpenSwoole\Coroutine::class : \Swoole\Coroutine::class;
        $configured = Mangan::fly('tokumx');
        $uri = (string)$configured->getConnection();
        $database = $configured->dbName;
        // Ensure metadata compilation is complete before entering request contexts.
        \Maslosoft\Mangan\Meta\ManganMeta::create(new ModelTransactional());
        $errors = [];
        $sessions = [];
        $run(static function () use ($coroutine, $uri, $database, &$errors, &$sessions): void {
            for ($i = 0; $i < 2; ++$i) {
                $coroutine::create(static function () use ($coroutine, $uri, $database, &$errors, &$sessions, $i): void {
                    $transaction = null;
                    try {
                        if (Transaction::isRunning()) {
                            throw new RuntimeException('A new coroutine inherited another active session.');
                        }
                        $mangan = Mangan::fly('tokumx');
                        $mangan->connectionString = $uri;
                        $mangan->dbName = $database;
                        $transaction = new Transaction(new ModelTransactional());
                        $sessions[$i] = Transaction::getRunningSession($mangan);
                        $coroutine::sleep(0.001);
                        if (Transaction::getRunningSession($mangan) !== $sessions[$i]) {
                            throw new RuntimeException('A suspended transaction picked up a sibling session.');
                        }
                    } catch (Throwable $error) {
                        $errors[] = $error;
                    } finally {
                        if ($transaction !== null) {
                            try {
                                $transaction->rollback();
                            } catch (Throwable $error) {
                                $errors[] = $error;
                            }
                        }
                    }
                });
            }
        });
        $this->assertSame([], array_map(static fn(Throwable $error): string => $error->getMessage(), $errors));
        $this->assertCount(2, $sessions);
        $this->assertNotSame($sessions[0], $sessions[1]);
        $this->assertFalse(Transaction::isRunning(), 'Coroutine transactions must not leak to the worker context.');
    }
}
