<?php

namespace EcomDev\MySQL2JSONL\Progress;

use EcomDev\MySQL2JSONL\ProgressNotifier;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class WorkerProgressConsumerTest extends TestCase implements ProgressNotifier
{
    use ProgressNotifierAware;

    #[Test]
    public function updatesTargetProgressBar()
    {
        $consumer = new WorkerProgressConsumer($this);
        $consumer->process(new FakeChannel([
            new WorkerMessage(WorkerMessageType::Start, ['test1', 10]),
            new WorkerMessage(WorkerMessageType::Update, ['test2', 10]),
            new WorkerMessage(WorkerMessageType::Finish, ['test1'])
        ]));

        $this->assertEquals(
            [
                ['start', 'test1', 10],
                ['update', 'test2', 10],
                ['finish', 'test1']
            ],
            $this->progress
        );
    }
}
