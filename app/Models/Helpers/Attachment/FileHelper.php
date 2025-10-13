<?php

namespace App\Models\Helpers\Attachment;

use File;
use App\Models\Attachment;
use Illuminate\Http\UploadedFile;

class FileHelper
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(private Attachment $attachment) {
        // 
    }

    ///////////////////////////////////////////////////////////////////////////
    public function save(UploadedFile $file): void {
        $targetName = $this->attachment->server_file_name;
        $targetPath = $this->getBasePath();

        $file->move($targetPath, $targetName);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getBasePath(): string {
        return $this->getRootPath()
             . DIRECTORY_SEPARATOR
             . $this->getRelativeBasePath();
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getRootPath(): string {
        return public_path(
            $this->getRelativeRootPath()
        );
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getRelativeRootPath(): string {
        return app()->runningUnitTests() ? app()->environment() : 'attachments';
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getRelativeBasePath(): string {
        return class_basename($this->attachment->attachable_type)
             . DIRECTORY_SEPARATOR
             . $this->attachment->attachable_id;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getFilePath(): string {
        return $this->getBasePath()
             . DIRECTORY_SEPARATOR
             . $this->attachment->server_file_name;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getThumbFilePath(): string {
        return $this->getBasePath()
             . DIRECTORY_SEPARATOR
             . $this->getThumbFileName();
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getThumbFileName(): string {
        $fileName  = File::name     ($this->attachment->server_file_name);
        $extension = File::extension($this->attachment->server_file_name);

        $thumbName = "{$fileName}-thumb";

        // files don't always have extensions
        if ($extension !== '') {
            $thumbName .= ".{$extension}";
        }

        return $thumbName;
    }


    ///////////////////////////////////////////////////////////////////////////
    public function getThumbUrl(): string {
        return $this->generateUrl(
            $this->getThumbFileName()
        );
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getUrl(): string {
        return $this->generateUrl(
            $this->attachment->server_file_name
        );
    }

    ///////////////////////////////////////////////////////////////////////////
    private function generateUrl(string $fileName): string {
        return url(
            $this->getRelativeRootPath()
          . DIRECTORY_SEPARATOR
          . $this->getRelativeBasePath()
          . DIRECTORY_SEPARATOR
          . $fileName
        );
    }

    ///////////////////////////////////////////////////////////////////////////
    public function delete(): void {
        File::delete( $this->getFilePath() );

        if ($this->attachment->isImage()) {
            File::delete( $this->getThumbFilePath() );
        }

        $this->reviseSubfolders();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once a file gets deleted, its parent folders should
     * be deleted too, if empty.
     */
    private function reviseSubfolders() {
        $parentIdFolder    = $this->getBasePath();
        $parentClassFolder = File::dirname($parentIdFolder);

        if (File::isEmptyDirectory($parentIdFolder)) {
            File::deleteDirectory($parentIdFolder);
        }
        
        if (File::isEmptyDirectory($parentClassFolder)) {
            File::deleteDirectory($parentClassFolder);
        }
    }

}