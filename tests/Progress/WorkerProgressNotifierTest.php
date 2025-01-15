<?php

namespace EcomDev\MySQL2JSONL\Progress;

use Amp\Cancellation;
use Amp\Sync\Channel;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class WorkerProgressNotifierTest extends TestCase
{
    #[Test]
    public function reportsProgressFromChildWorker()
    {
        $channel = new FakeChannel([]);
        $notifier = new WorkerProgressNotifier($channel, 1);
        $notifier->start('test1', 10);
        $notifier->start('test2', 100);
        $notifier->update('test1', 100);
        $notifier->finish('test1');
        $notifier->update('test2', 5);
        $notifier->finish('test2');

        $this->assertEquals(
            [
                new WorkerMessage(WorkerMessageType::Start, ['test1', 10]),
                new WorkerMessage(WorkerMessageType::Start, ['test2', 100]),
                new WorkerMessage(WorkerMessageType::Update, ['test1', 100]),
                new WorkerMessage(WorkerMessageType::Finish, ['test1']),
                new WorkerMessage(WorkerMessageType::Update, ['test2', 5]),
                new WorkerMessage(WorkerMessageType::Finish, ['test2'])
            ],
            $channel->messages
        );
    }


    public function receive(?Cancellation $cancellation = null): mixed
    {
        throw new \LogicException('Should not be called');
    }

    public function send(mixed $data): void
    {
        $this->progress[] = $data;
    }

    public function close(): void
    {
        throw new \LogicException('Should not be called');
    }

    public function isClosed(): bool
    {
        throw new \LogicException('Should not be called');
    }

    public function onClose(\Closure $onClose): void
    {
        throw new \LogicException('Should not be called');
    }
}
