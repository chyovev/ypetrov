<?php

namespace App\Models;

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

}
