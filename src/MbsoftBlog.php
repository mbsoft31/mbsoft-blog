<?php

namespace Mbsoft31\MbsoftBlog;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mbsoft31\MbsoftBlog\Models\Post;

class MbsoftBlog
{
    /**
     * @param $user
     * @param string $title
     * @return Post|null
     */
    public function createNewPost($user, string $title = 'Untitled post') : ?Post
    {
        $slug = $this->generatePostSlug($title);

        $post = Post::factory()
            ->for($user)
            ->state([
                'title' => $title,
                'slug' => $slug,
                'thumbnail' => null,
                'published' => false,
            ])
            ->count(1)
            ->create()
            ->last();

        return $post;
    }

    /**
     * @param string $sluggable
     * @return string
     */
    private function generatePostSlug(string $sluggable = ''): string
    {
        $slug = Str::slug($sluggable, '_');
        $n = 0;
        while (Post::where('slug', $slug)->exists()) {
            $slug = Str::slug($sluggable, '_') . '_' . ++$n;
        }

        return $slug;
    }

    /**
     * @param Post $post
     * @param array $data
     * @return bool
     */
    public function updatePost(Post $post, array $data = []) : bool
    {

        if ( ! (count($data) > 0) ) return false;

        $dirty = false;

        if (Arr::has($data, 'title'))
        {
            $post->title = $data['title'];
            $post->slug = $this->generatePostSlug($data['title']);;
            $dirty = true;
        }

        if (Arr::has($data, 'description'))
        {
            $post->description()
                ->updateOrCreate(
                    Arr::only($data, ['description'])
                );
        }

        if (Arr::has($data, 'thumbnail'))
        {
            $post = $this->updatePostThumbnail($post, $data['thumbnail']);
            $dirty = true;
        }

        if (Arr::has($data, 'short_description'))
        {
            $post->short_description()
                ->updateOrCreate(
                    Arr::only($data, ['short_description'])
                );
        }

        return $dirty ? $post->save() : false;

    }

    public function updatePostThumbnail(Post $post, UploadedFile $thumbnail = null)
    {
        if (! $thumbnail) return $post;

        $url = $thumbnail->store('public');
        if ($url)
            $post->thumbnail = $url;

        return $post;
    }

    /**
     * @param Post $post
     * @param $user
     * @return Post
     */
    public function viewedBy(Post $post, $user = null): Post
    {

        if ( is_null($user) > 0 ) {
            return $post;
        }

        if (
            $post->user_views()
                ->where('user_id', $user->id)
                ->count() > 0
        )
        {
            return $post;
        }

        $post->user_views()->updateOrCreate([
            'user_id' => $user->id,
        ]);

        if (! $post->views )
        {
            $post->views()->create([
                'views' => 1,
            ]);
        }else {
            $post->views()->increment('views');
        }

        return $post->refresh();
    }


}
