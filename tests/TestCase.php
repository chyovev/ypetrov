<?php

namespace Tests;

use File;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    ///////////////////////////////////////////////////////////////////////////
    public function tearDown(): void {
        $this->deleteTestingFolder();

        // call the parent tearDown to close any hanging transactions
        parent::tearDown();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Files uploaded during testing are stored in the testing folder
     * which should be purged once the tests are completed.
     * 
     * @return void
     */
    private function deleteTestingFolder(): void {
        $folder = $this->getTestingPath();

        File::deleteDirectory($folder);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getTestingPath(): string {
        return public_path( app()->environment() );
    }

}
