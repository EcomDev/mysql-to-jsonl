<?php

namespace EcomDev\MySQL2JSONL\Progress;

use Amp\Cancellation;
use Amp\Sync\Channel;
use Amp\Sync\ChannelException;

final class FakeChannel implements Channel
{
    public function __construct(public array $messages)
    {
    }

    public function receive(?Cancellation $cancellation = null): mixed
    {
        if (empty($this->messages)) {
            throw new ChannelException('No messages in channel');
        }

        return array_shift($this->messages);
    }

    public function send(mixed $data): void
    {
        $this->messages[] = $data;
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
