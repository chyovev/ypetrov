<?php

namespace App\Models;

use App\Models\Interfaces\Attachable;
use App\Models\Interfaces\Commentable;
use App\Models\Interfaces\SEO;
use App\Models\Interfaces\Statsable;
use App\Models\Traits\HasActiveState;
use App\Models\Traits\HasAttachments;
use App\Models\Traits\HasComments;
use App\Models\Traits\HasStats;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model implements Attachable, Commentable, Statsable, SEO
{

    /**
     * All static pages with their respective IDs.
     */
    const BIOGRAPHY_ID    = 1,
          CHRESTOMATHY_ID = 2;
          

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
     * The HasComments trait defines a polymorphic
     * relationship to the Comment model and registers
     * a delete-event observer.
     */
    use HasComments;

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

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Static pages don't have an active state
     * so they can always be liked.
     * 
     * @return bool
     */
    public function canBeCommentedOn(): bool {
        return true;    
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Static pages don't have an active state
     * so they can always be liked.
     * 
     * @return bool
     */
    public function canBeLiked(): bool {
        return true;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaTitle(): ?string {
        return $this->title;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaDescription(): ?string {
        return $this->text;
    }
    
}
