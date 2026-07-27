<?php

namespace Zerp\Account\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Zerp\Account\Providers\AccountServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [AccountServiceProvider::class];
    }
}
