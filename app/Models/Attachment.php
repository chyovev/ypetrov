<?php

namespace App\Models;

use LogicException;
use App\Models\Helpers\Attachment\FileHelper;
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
    public function getFileHelper(): FileHelper {
        return new FileHelper($this);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Generate a public URL to the attachment.
     * 
     * @return string
     */
    public function getURL(): string {
        return $this->getFileHelper()->getUrl();
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
     * @throws LogicException – attachment not an image
     */
    public function getThumbURL(): ?string {
        return $this->getFileHelper()->getThumbUrl();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the width of an image attachment.
     * If the attachment is not an image, null will be returned.
     */
    public function getWidth(): ?int {
        return $this->getFileHelper()->getWidth();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the height of an image attachment.
     * If the attachment is not an image, null will be returned.
     */
    public function getHeight(): ?int {
        return $this->getFileHelper()->getHeight();
    }

}
