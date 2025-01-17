<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Progress;

use EcomDev\MySQL2JSONL\Progress\ExportProgressNotifier;
use EcomDev\MySQL2JSONL\TestableConsoleOutput;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ExportProgressNotifierTest extends TestCase
{
    #[Test]
    public function setsEachTableOnOwnLine()
    {
        $output = TestableConsoleOutput::create();
        $notifier = (new ExportProgressNotifier($output))->withRealtime();
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
        $notifier = (new ExportProgressNotifier($output))->withRealtime();

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
        $notifier = (new ExportProgressNotifier($output))->withRealtime();

        $notifier->start('table_one', 100);
        $notifier->start('table_two', 99);
        $notifier->update('table_two', 40);
        $notifier->finish('table_two');

        $this->assertEquals(
            [
                'Exporting `table_one` [🚀---------------------------] 0% ',
                'Exported `table_two` [============================] 100% ',
            ],
            $output->flushOutput()
        );
    }
}
