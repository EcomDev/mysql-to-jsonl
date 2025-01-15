<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL\Export;

use EcomDev\MySQL2JSONL\TempDirectoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function Amp\File\filesystem;

class JsonExportOutputFactoryTest extends TestCase
{
    use TempDirectoryFixture;

    #[Test]
    public function createsDirectoryForExportOnFirstExporter()
    {
        $factory = new JsonExportOutputFactory($this->path);
        $factory->create('test');

        $this->assertTrue(
            is_dir($this->path)
        );
    }

    #[Test]
    public function createsWritableFileInDirectoryWithTableName()
    {
        $factory = new JsonExportOutputFactory($this->path);
        $factory->create('test');
        $this->assertTrue(
            is_file($this->path . DIRECTORY_SEPARATOR . 'test.jsonl')
            && is_writable($this->path . DIRECTORY_SEPARATOR . 'test.jsonl')
        );
    }
}
