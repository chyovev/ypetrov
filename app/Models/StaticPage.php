<?php

namespace App\Models;

use App\Utils\Seo;
use App\Models\Builders\StaticPageBuilder;
use App\Models\Interfaces\Attachable;
use App\Models\Interfaces\Commentable;
use App\Models\Interfaces\MetaData;
use App\Models\Interfaces\Statsable;
use App\Models\Traits\HasAttachments;
use App\Models\Traits\HasComments;
use App\Models\Traits\HasStats;
use App\Observers\AttachableObserver;
use App\Observers\CommentableObserver;
use App\Observers\StatsableObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseEloquentBuilder(StaticPageBuilder::class)]
#[ObservedBy([CommentableObserver::class, AttachableObserver::class, StatsableObserver::class])]
#[Fillable(['title', 'text'])]
class StaticPage extends Model implements Attachable, Commentable, Statsable, MetaData
{

    /**
     * All static pages with their respective IDs.
     */
    const BIOGRAPHY_ID    = 1,
          CHRESTOMATHY_ID = 2;
          

    use HasFactory;

    use HasAttachments;

    use HasComments;

    use HasStats;

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Static pages don't have an active state
     * so they can always be commented on.
     */
    public function canBeCommentedOn(): bool {
        return true;    
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Static pages don't have an active state
     * so they can always be liked.
     */
    public function canBeLiked(): bool {
        return true;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getSeo(): Seo {
        return new Seo($this->title, $this->text);
    }
    
}
