<?php

namespace App\Models;

use App\Models\Interfaces\Attachable;
use App\Models\Interfaces\Statsable;
use App\Models\Traits\HasActiveState;
use App\Models\Traits\HasAttachments;
use App\Models\Traits\HasStats;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model implements Attachable, Statsable
{

    /**
     * All static pages with their respective IDs.
     */
    const BIOGRAPHY_ID = 1;
          

    use HasFactory;

    /**
     * Add shortcut query builder method
     * to filter out inactive elements.
     */
    use HasActiveState;

    /**
     * The HasAttachments trait defines a polymorphic
     * relationship to the Attachment model and registers
     * a delete-event observer.
     */
    use HasAttachments;

    /**
     * The HasStats trait defines a polymorphic
     * relationship to the Stats model and registers
     * a delete-event observer.
     */
    use HasStats;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    public $fillable = [
        'title', 'text',
    ];
    
}
