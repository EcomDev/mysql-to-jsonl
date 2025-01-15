<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Progress;

use EcomDev\MySQL2JSONL\Progress\ConsoleProgressNotifier;
use EcomDev\MySQL2JSONL\TestableConsoleOutput;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ConsoleProgressNotifierTest extends TestCase
{
    #[Test]
    public function setsEachTableOnOwnLine()
    {
        $output = TestableConsoleOutput::create();
        $notifier = $this->createNotifier($output);
        $notifier->start('table_one', 100);
        $notifier->start('table_two', 99);

        $this->assertEquals(
            [
                'Exporting `table_one` [🚀---------------------------] 0% ',
                'Exporting `table_two` [🚀---------------------------] 0% ',
            ],
            $output->flushOutput()
        );
    }

    #[Test]
    public function updatesProgressBar()
    {
        $output = TestableConsoleOutput::create();
        $notifier = $this->createNotifier($output);

        $notifier->start('table_one', 100);
        $notifier->start('table_two', 99);
        $notifier->update('table_two', 40);

        $this->assertEquals(
            [
                'Exporting `table_one` [🚀---------------------------] 0% ',
                'Exporting `table_two` [===========🚀----------------] 40% ',
            ],
            $output->flushOutput()
        );
    }

    #[Test]
    public function notifiesWhenUploadHasFinished()
    {
        $output = TestableConsoleOutput::create();
        $notifier = $this->createNotifier($output)->withRealtime();

        $notifier->start('table_one', 100);
        $notifier->start('table_two', 99);
        $notifier->update('table_two', 40);
        $notifier->finish('table_two');

        $this->assertEquals(
            [
                'Exported 99 rows in `table_two`',
                'Exporting `table_one` [🚀---------------------------] 0% ',
            ],
            $output->flushOutput()
        );
    }

    public function createNotifier(TestableConsoleOutput $output, string $inProgress = 'Exporting', string $finished = 'Exported'): ConsoleProgressNotifier
    {
        return (new ConsoleProgressNotifier($output, $inProgress, $finished))->withRealtime();
    }
}
