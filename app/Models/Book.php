<?php

namespace App\Models;

use App\Models\Interfaces\Attachable;
use App\Models\Interfaces\Commentable;
use App\Models\Interfaces\Statsable;
use App\Models\Interfaces\SEO;
use App\Models\Traits\HasActiveState;
use App\Models\Traits\HasAttachments;
use App\Models\Traits\HasComments;
use App\Models\Traits\HasStats;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model implements Attachable, Commentable, Statsable, SEO
{
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
        'is_active', 'title', 'slug', 'publisher', 'publish_year', 'order',
    ];

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A book usually consists of multiple poems, and a single poem
     * can be part of multiple books. Therefore, a pivot many-to-many
     * table is used (book_poem) whose additional columns can also be
     * fetched using the withPivot() and withTimestamps() methods.
     * 
     * @return BelongstoMany
     */
    public function poems(): BelongsToMany {
        return $this
            ->belongsToMany(Poem::class)
            ->withPivot('order')
            ->orderByPivot('order')
            ->withTimestamps();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A book can be commented on if it's marked as active.
     * 
     * @return bool
     */
    public function canBeCommentedOn(): bool {
        return $this->isActive();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A book can be liked if it's marked as active.
     * 
     * @return bool
     */
    public function canBeLiked(): bool {
        return $this->isActive();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Using a poem's slug, try to find it in the collection
     * of associated poems.
     * 
     * @throws ItemNotFoundException
     * @param  string $slug
     * @return Poem
     */
    public function getPoemBySlug(string $slug): Poem {
        return $this->poems->filter(function(Poem $poem) use($slug) {
            return ($poem->slug === $slug) ;
        })->firstOrFail();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * When poems are being synced with a book, they need to be
     * persisted in the order in which their IDs are being passed
     * to the method using the relationship's pivot column 'order'.
     * 
     * @param  int[] $ids – poem IDs
     * @return array
     */
    public function syncPoemsInOrder($ids): array {
        $data  = [];
        $order = 1;

        foreach ($ids as $id) {
            $data[$id] = [
                'order' => $order,
            ];

            $order++;
        }

        return $this->poems()->sync($data);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Take the first image from the book's attachments
     * and use it as a cover (if uploaded).
     * 
     * @return string|null
     */
    public function getCoverImage(): ?string {
        return $this->getFirstImage()?->getURL();
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaTitle(): ?string {
        $title = $this->title;

        if ($this->publish_year) {
            $title .= " ({$this->publish_year})";
        }

        return $title;
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getMetaDescription():? string {
        $description = $this->getMetaTitle();

        if ($this->publisher) {
            $description .= ", издателство {$this->publisher}";
        }

        return $description;
    }
}
