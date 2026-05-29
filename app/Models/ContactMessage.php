<?php

namespace App\Models;

use App\Models\Traits\HasVisitor;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

#[Fillable(['is_read', 'name', 'email', 'message'])]
class ContactMessage extends Model
{
    use HasFactory, HasVisitor;

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check if a contact message is unread by reverting
     * the is_read boolean flag.
     * 
     * @return bool
     */
    public function isUnread(): bool {
        return ( ! $this->is_read);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Mark a contact message as read.
     * 
     * @return bool
     */
    public function markAsRead(): bool {
        return $this->update(['is_read' => true]);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function scopeFilterBy(Builder $query, string $search): void {
        $search = "%{$search}%";

        $query->where('name',    'LIKE', $search)
            ->orWhere('email',   'LIKE', $search)
            ->orWhere('message', 'LIKE', $search);
    }

}
