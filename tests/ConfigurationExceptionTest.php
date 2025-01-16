<?php

/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\MySQL2JSONL;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ConfigurationExceptionTest extends TestCase
{
    #[Test]
    public function outputsEachValidationErrorInSummaryLine()
    {
        $error = ConfigurationException::fromErrors(
            [
                'connection.host' => 'Host is a required field',
                'connection.username' => 'Username is empty',
            ],
        );

        $output = TestableConsoleOutput::create();

        $error->output($output);
        $this->assertEquals(
            [
              '[Configuration validation failed] ',
               'connection.host: Host is a required field',
               'connection.username: Username is empty'
            ],
            $output->flushError()
        );
    }
}
