<?php

namespace App\Models;

use App\Models\Traits\HasComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PressArticle extends Model
{
    use HasFactory;

    /**
     * The HasComments trait defines a polymorphic
     * relationship to the Comment model.
     */
    use HasComments;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    public $fillable = [
        'is_active', 'title', 'slug', 'press', 'publish_date', 'text', 'order',
    ];

}
