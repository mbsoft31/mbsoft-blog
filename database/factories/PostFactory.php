<?php

namespace Mbsoft31\MbsoftBlog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Mbsoft31\MbsoftBlog\Models\Post;
use Mbsoft31\MbsoftBlog\Models\User;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        $post_title = $this->faker->sentence;

        return [
            'title' => $post_title,
            'slug' => Str::slug($post_title),
            'thumbnail' => null,
            'published' => false,
            'user_id' => User::factory(),
        ];
    }
}

