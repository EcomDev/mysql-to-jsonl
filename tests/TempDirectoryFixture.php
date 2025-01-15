<?php

namespace EcomDev\MySQL2JSONL;

use SplFileObject;

trait TempDirectoryFixture
{
    private string $path;
    private string $file;

    protected function setUp(): void
    {
        $this->path = implode(DIRECTORY_SEPARATOR, [sys_get_temp_dir(), uniqid('test-directory')]);
    }

    private function file(): SplFileObject
    {
        mkdir($this->path, recursive: true);
        $this->file = $this->path . DIRECTORY_SEPARATOR . uniqid('test-file');
        return new SplFileObject(
            $this->file,
            'w+'
        );
    }

    private function writeFile(string $name, array $content): SplFileObject
    {
        if (!is_dir($this->path)) {
            mkdir($this->path, recursive: true);
        }
        $path = $this->path . DIRECTORY_SEPARATOR . $name;
        $file = new SplFileObject($path, 'w');

        foreach ($content as $line) {
            $file->fwrite(json_encode($line) . PHP_EOL);
        }

        return new SplFileObject($path, 'r');
    }

    protected function tearDown(): void
    {
        if (is_dir($this->path)) {
            foreach (glob($this->path . DIRECTORY_SEPARATOR . '*') as $file) {
                unlink($file);
            }
            rmdir($this->path);
        }
    }
}
