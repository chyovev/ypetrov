<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Laravel has multiple path helper functions such as app_path(),
     * database_path(), etc., but it lacks a shortcut for the testing
     * folder.
     */

    ///////////////////////////////////////////////////////////////////////////
    protected function getAssetsPath(): string {
        return $this->getTestsPath() . DIRECTORY_SEPARATOR . 'assets';
    }

    ///////////////////////////////////////////////////////////////////////////
    protected function getTestsPath(): string {
        return base_path('tests');
    }
}
