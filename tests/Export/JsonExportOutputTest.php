<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

use Amp\File\File;
use EcomDev\MySQL2JSONL\TempDirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SplFileObject;
use SplTempFileObject;

use function Amp\File\filesystem;

class JsonExportOutputTest extends TestCase
{
    use TempDirectoryFixture;

    #[Test]
    public function outputsFileHeader()
    {
        $output = new JsonExportOutput($this->file());
        $output->open(['one', 'two', 'three']);

        $this->assertEquals(
            '["one","two","three"]' . PHP_EOL,
            $this->readFile()
        );
    }
    #[Test]
    public function appendsRowsToTheFile()
    {
        $output = new JsonExportOutput($this->file());
        $output->open(['one', 'two', 'three']);
        $output->write([1.1, 2.1, 3.1]);
        $output->write([1.2, 2.2, 3.2]);
        $output->write([1.3, 2.3, 3.3]);

        $this->assertEquals(
            implode(
                PHP_EOL,
                [
                    '["one","two","three"]',
                    '[1.1,2.1,3.1]',
                    '[1.2,2.2,3.2]',
                    '[1.3,2.3,3.3]',
                ]
            ) . PHP_EOL,
            $this->readFile()
        );
    }

    #[Test]
    public function errorsWhenWrittenBeforeOpen()
    {
        $output = new JsonExportOutput($this->file());
        $this->expectExceptionObject(new ExportOutputWrittenBeforeOpenException());

        $output->write([1.1, 2.1, 3.1]);
    }

    #[Test]
    public function errorsWhenColumnCountDoesNotMatchHeader()
    {
        $output = new JsonExportOutput($this->file());
        $this->expectExceptionObject(new ExportOutputColumCountDoesNotMatchException());
        $output->open(['one', 'two']);
        $output->write([1.1, 2.1, 3.1]);
    }

    #[Test]
    public function closesFile()
    {
        $file = $this->file();
        $file->flock(LOCK_EX);

        $output = new JsonExportOutput($file);
        unset($file);

        $output->open(['one', 'two', 'three']);
        $output->write([1.1, 2.1, 3.1]);
        $output->close();

        $this->assertTrue(
            flock(fopen($this->file, 'r'), LOCK_EX | LOCK_NB)
        );
    }

    private function readFile(): string
    {
        return file_get_contents($this->file);
    }
}
