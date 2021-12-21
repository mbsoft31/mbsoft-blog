<?php

namespace Mbsoft31\MbsoftBlog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'published' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function description()
    {
        return $this->hasOne(PostDescription::class);
    }

    public function short_description()
    {
        return $this->hasOne(PostShortDescription::class);
    }

    public function views() : HasOne
    {
        return $this->HasOne(PostView::class);
    }

    public function user_views() : HasMany
    {
        return $this->hasMany(UserView::class);
    }

}
