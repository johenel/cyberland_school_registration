<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected static bool $migrated = false;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure we're using the testing environment
        if (app()->environment('testing') && ! static::$migrated) {
            $this->artisan('migrate:fresh');
            static::$migrated = true;
        }
    }
}
