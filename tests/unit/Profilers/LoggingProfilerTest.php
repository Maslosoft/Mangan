<?php

namespace Profilers;

use Codeception\Test\Unit;
use Maslosoft\Mangan\EntityManager;
use Maslosoft\Mangan\Finder;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Profillers\LoggingProfiler;
use Maslosoft\ManganTest\Models\WithBaseAttributes;
use Psr\Log\AbstractLogger;
use Stringable;

class LoggingProfilerTest extends Unit
{
    public function testLogsDriverCursorWithoutConsumingResults(): void
    {
        $model = new WithBaseAttributes();
        (new EntityManager($model))->insert();
        $logger = new class extends AbstractLogger {
            public array $records = [];

            public function log($level, string|Stringable $message, array $context = []): void
            {
                $this->records[] = compact('level', 'message', 'context');
            }
        };
        $mangan = clone Mangan::fromModel($model);
        $mangan->setLogger($logger);
        $mangan->setProfiler(new LoggingProfiler());

        $found = (new Finder($model, null, $mangan))->findAll();

        $this->assertCount(1, $found);
        $this->assertSame((string) $model->_id, (string) $found[0]->_id);
        $this->assertCount(1, $logger->records);
        $this->assertSame('info', $logger->records[0]['level']);
        $this->assertArrayHasKey('cursorId', $logger->records[0]['context']);
        $this->assertArrayHasKey('server', $logger->records[0]['context']);
    }
}
