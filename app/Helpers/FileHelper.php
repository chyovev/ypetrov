<?php

namespace App\Helpers;

use File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

/**
 * The FileHelper class is used during attachment upload
 * in order to extract information from a file such as
 * mime type, size, extension, etc.
 */

class FileHelper
{

    /**
     * The name of the file being processed,
     * without extension.
     * 
     * @var string
     */
    private string $fileName;

    /**
     * The name of the file being processed,
     * including extension.
     * 
     * @var string
     */
    private string $baseName;

    /**
     * The extension of the file being processed.
     * Field is optional.
     * 
     * @var string
     */
    private ?string $extension;

    /**
     * The size of the file being processed.
     * 
     * @var int
     */
    private int $fileSize;

    /**
     * The MIME type of the file being processed.
     * 
     * @var string
     */
    private string $mimeType;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * @throws FileNotFoundException
     */
    public function __construct(string $filePath, string $fileName = null) {
        if ( ! File::exists($filePath)) {
            throw new FileNotFoundException("File could not be found {$filePath}");
        }

        // if no original file name was passed,
        // extract the file name from the path
        if (is_null($fileName)) {
            $fileName = File::basename($filePath);
        }

        $this->extractDataFromFile($filePath);
        $this->extractDataFromFileName($fileName);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function extractDataFromFile(string $filePath): void {
        $this->fileSize  = File::size($filePath);
        $this->mimeType  = File::mimeType($filePath);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function extractDataFromFileName(string $fileName): void {
        $this->fileName  = File::name($fileName);
        $this->baseName  = File::basename($fileName);
        $this->extension = File::extension($fileName);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getFileName(): string {
        return $this->fileName;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getBaseName(): string {
        return $this->baseName;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getExtension(): ?string {
        return $this->extension;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getFileSize(): int {
        return $this->fileSize;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMimeType(): string {
        return $this->mimeType;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Move the source file to another location.
     * 
     * @param  string $sourcePath – full source path, name included
     * @param  string $targetPath – full target path, name included
     * @return bool
     */
    public function moveFile(string $sourcePath, string $targetPath): bool {
        $this->createTargetDirectory($targetPath);

        return File::move($sourcePath, $targetPath);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Before moving a file to a target directory, make sure it exists.
     * 
     * @param  string $targetPath – full target path, name included
     * @return void
     */
    private function createTargetDirectory(string $targetPath): void {
        $targetDir = File::dirname($targetPath);

        File::ensureDirectoryExists($targetDir);
    }
}