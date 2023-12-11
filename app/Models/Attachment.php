<?php

namespace App\Models;

use File;
use LogicException;
use Nette\Utils\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    public $fillable = [
        'original_file_name', 'server_file_name', 'caption',
        'file_size', 'mime_type', 'order',
    ];

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Some models are attachable, i.e. they have a morphMany
     * relationship to Attachment model, but it is also possible
     * to get the main object through a attachment using the
     * reversed morphTo relationship.
     * 
     * @return MorphTo
     */
    public function attachable(): MorphTo {
        return $this->morphTo('attachable');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once an attachment record gets deleted, the referenced file
     * and its thumbnail should also be deleted from the server.
     * If the attachable subfolder has no contents afterwards,
     * delete it as well.
     * 
     * NB! This method is called automatically by the Attachment
     *     observer.
     * 
     * @see \App\Observers\AttachmentObserver :: deleted()
     */
    public function deleteFile(): void {
        File::delete( $this->getServerFilePath() );

        if ($this->isImage()) {
            File::delete( $this->getThumbFilePath() );
        }

        $this->deleteFolderIfEmpty();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once an attachment gets deleted together with its file,
     * if the remaining folder is empty, it should be purged, too.
     * 
     * @return void
     */
    public function deleteFolderIfEmpty(): void {
        $subfolder   = $this->getAbsolutePath();
        $modelFolder = File::dirname($subfolder);

        // first delete the FK subfolder if empty (e.g. /attachments/PressArticle/4)
        $this->deleteIfEmpty($subfolder);
        
        // then delete the model folder if empty (e.g. /attachments/PressArticle)
        $this->deleteIfEmpty($modelFolder);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Delete a folder if it's empty.
     * 
     * @param string $folderPath
     */
    private function deleteIfEmpty(string $folderPath): void {
        // if the folder was deleted manually,
        // it won't exist anymore – simply abort
        if ( ! File::isDirectory($folderPath)) {
            return;
        }

        if (File::isEmptyDirectory($folderPath)) {
            File::deleteDirectory($folderPath);
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The server full path to a file consists of its attachable
     * object's subfolder + the server file name.
     * 
     * @return string
     */
    public function getServerFilePath(): string {
        $absolutePath = $this->getAbsolutePath();

        return $absolutePath . DIRECTORY_SEPARATOR . $this->server_file_name;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The absolute path of an attachment consists of the
     * absolute path to the public folder + the relative
     * path of the attachment.
     * 
     * @return string
     */
    public function getAbsolutePath(): string {
        return public_path( $this->getRelativePath() );
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * All attachments are stored in a public folder bearing the
     * name of the model's table (attachments) and distributed
     * within subfolders based on the attachable object's class
     * name and its primary key (foreign key for this model).
     * Example:
     * 
     *     attachments/PressArticle/4
     * 
     * NB! During unit tests the common folder is called
     *     after the testing environment (testing).
     * 
     * @return string
     */
    public function getRelativePath(): string {
        $commonFolder = app()->runningUnitTests() ? app()->environment() : $this->getTable();
        $sourceClass  = class_basename($this->attachable_type);
        $foreignKeyId = $this->attachable_id;

        return $commonFolder
            . DIRECTORY_SEPARATOR
            . $sourceClass
            . DIRECTORY_SEPARATOR
            . $foreignKeyId;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Generate a public URL to the attachment.
     * 
     * @return string
     */
    public function getURL(): string {
        return url( $this->getRelativePath()) . "/{$this->server_file_name}";
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check if the attachment is an image.
     * Thumbnails are automatically created for image attachments
     * upon attachment upload.
     * 
     * @return bool
     */
    public function isImage(): bool {
        return $this->hasType('image');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check if an attachment has a specific MIME type.
     * 
     * @param string $mimeType – regex supported
     * @return bool
     */
    public function hasType(string $mimeType): bool {
        $regex = '/' . preg_quote($mimeType, '/') . '/';
        
        return (bool) preg_match($regex, $this->mime_type);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Generate a thumbnail for an image.
     * 
     * @throws LogicException – attachment not an image
     */
    public function generateThumbnail(): void {
        $sourcePath = $this->getServerFilePath();
        $targetPath = $this->getThumbFilePath();

        // resize & crop to 60x60 px
        $image = Image::fromFile($sourcePath);
        $image
            ->resize(60, 60, Image::OrBigger)
            ->crop(0, 0, 60, 60)
            ->sharpen()
            ->save($targetPath);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * @throws LogicException – attachment not an image
     */
    public function getThumbFilePath(): string {
        $dirName   = $this->getAbsolutePath();
        $thumbName = $this->getThumbFileName();

        return $dirName . DIRECTORY_SEPARATOR . $thumbName;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * @throws LogicException – attachment not an image
     */
    public function getThumbURL(): ?string {
        $dirName   = $this->getRelativePath();
        $thumbName = $this->getThumbFileName();

        return url("{$dirName}/{$thumbName}");
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The thumb file name consists of the original file's server name
     * followed by a '-thumb' suffix before its extension (if any).
     * 
     * NB! Keep in mind that this applies only to images, in all
     *     other cases an exception will be thrown.
     * 
     * @throws LogicException – attachment not an image
     * @return string
     */
    private function getThumbFileName(): string {
        if ( ! $this->isImage()) {
            throw new LogicException("Attachment #{$this->id} is not an image");
        }

        $originalFileName      = File::name($this->server_file_name);
        $originalFileExtension = File::extension($this->server_file_name);

        $thumbName = "{$originalFileName}-thumb";

        // if the original file has an extension,
        // use it for the thumb, too
        if ($originalFileExtension) {
            $thumbName .= ".{$originalFileExtension}";
        }

        return $thumbName;
    }

}
