<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mbsoft31\MbsoftBlog\Facades\MbsoftBlog;
use Mbsoft31\MbsoftBlog\Models\User;

it('can create new untitled post', function () {

    $user = User::factory()->count(1)->create()->last();

    $post = MbsoftBlog::createNewPost($user);

    expect($post->title)->toBe('Untitled post');
    expect($post->slug)->toBe(Str::slug('Untitled post', '_'));
    expect($post->published)->toBeFalse();
    expect($post->user->id)->toBe($user->id);
});

it('create and change slug if the slug of new post exists', function () {

    $user = User::factory()->count(1)->create()->last();

    MbsoftBlog::createNewPost($user);
    MbsoftBlog::createNewPost($user);
    $post = MbsoftBlog::createNewPost($user);

    expect($post->title)->toBe('Untitled post');
    expect($post->slug)->toBe(Str::slug('Untitled post 2', '_'));
    expect($post->published)->toBeFalse();
    expect($post->user->id)->toBe($user->id);
});

it('can update existing post', function () {

    $storage = Storage::fake('public');
    $user = User::factory()->count(1)->create()->last();
    $post = MbsoftBlog::createNewPost($user);
    $data = [
        'title' => 'hello blog',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam fugiat iure provident saepe voluptate. Aliquam amet doloremque fugiat ipsum iusto laboriosam maiores maxime, nam repellat rerum. Accusamus deleniti labore magnam natus sint ullam velit! Assumenda atque, delectus doloremque error facilis molestias necessitatibus neque nesciunt nihil nobis, nulla soluta suscipit voluptas?',
        'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci beatae dolor excepturi laboriosam laborum magni nemo quaerat quam quo veniam.',
        'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg', 100, 100),
    ];

    MbsoftBlog::updatePost($post, $data);

    expect($post->title)->toBe('hello blog');
    expect($post->slug)->toBe(Str::slug('hello blog', '_'));
    expect($post->published)->toBeFalse();
    expect($post->user->id)->toBe($user->id);
    expect($post->description)->not()->toBeNull();
    expect($post->short_description)->not()->toBeNull();
});

it('can create post view', function () {

    $storage = Storage::fake('public');
    $user = User::factory()->count(1)->create()->last();
    $post = MbsoftBlog::createNewPost($user);
    $data = [
        'title' => 'hello blog',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam fugiat iure provident saepe voluptate. Aliquam amet doloremque fugiat ipsum iusto laboriosam maiores maxime, nam repellat rerum. Accusamus deleniti labore magnam natus sint ullam velit! Assumenda atque, delectus doloremque error facilis molestias necessitatibus neque nesciunt nihil nobis, nulla soluta suscipit voluptas?',
        'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci beatae dolor excepturi laboriosam laborum magni nemo quaerat quam quo veniam.',
        'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg'),
    ];
    MbsoftBlog::updatePost($post, $data);

    $post = MbsoftBlog::viewedBy($post, $user);

    expect($post->views->views)->toBe(1);
});

it('can create post view increment only if another user viewed it', function () {

    $views_count = 4;

    $storage = Storage::fake('public');

    $users = User::factory()->count($views_count)->create();

    $post = MbsoftBlog::createNewPost($users->first());
    $data = [
        'title' => 'hello blog',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam fugiat iure provident saepe voluptate. Aliquam amet doloremque fugiat ipsum iusto laboriosam maiores maxime, nam repellat rerum. Accusamus deleniti labore magnam natus sint ullam velit! Assumenda atque, delectus doloremque error facilis molestias necessitatibus neque nesciunt nihil nobis, nulla soluta suscipit voluptas?',
        'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci beatae dolor excepturi laboriosam laborum magni nemo quaerat quam quo veniam.',
        'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg'),
    ];
    MbsoftBlog::updatePost($post, $data);

    foreach ($users as $user) {
        $post = MbsoftBlog::viewedBy($post, $user);
    }

    expect($post->views->views)->toBe($views_count);

    // this user already viewed the post
    $user = $users->first();

    $post = MbsoftBlog::viewedBy($post, $user);

    // views count must not change
    expect($post->views->views)->toBe($views_count);

    dump($post->thumbnail);
});

