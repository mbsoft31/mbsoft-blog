<?php

namespace Mbsoft31\MbsoftBlog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
