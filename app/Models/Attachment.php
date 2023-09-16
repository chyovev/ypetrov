<?php

namespace App\Models;

use File;
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
     * @return morphTo
     */
    public function attachable(): MorphTo {
        return $this->morphTo('attachable');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once an attachment record gets deleted, the referenced file
     * should also be deleted from the server. If the attachable
     * subfolder has no contents afterwards, delete it as well.
     * 
     * NB! This method is called automatically by the Attachment
     *     observer.
     * 
     * @see \App\Observers\AttachmentObserver :: deleted()
     */
    public function deleteFile(): void {
        File::delete( $this->getServerFilePath() );

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
        if (File::isEmptyDirectory($subfolder)) {
            File::deleteDirectory($subfolder);
        }

        // then delete the model folder if empty (e.g. /attachments/PressArticle)
        if (File::isEmptyDirectory($modelFolder)) {
            File::deleteDirectory($modelFolder);
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
     * @return string
     */
    public function getRelativePath(): string {
        $sourceClass  = class_basename($this->attachable_type);
        $foreignKeyId = $this->attachable_id;

        return $this->getTable()
            . DIRECTORY_SEPARATOR
            . $sourceClass
            . DIRECTORY_SEPARATOR
            . $foreignKeyId;
    }

}
