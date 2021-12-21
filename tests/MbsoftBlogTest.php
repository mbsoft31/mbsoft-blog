<?php

use Illuminate\Support\Str;
use Mbsoft31\MbsoftBlog\Facades\MbsoftBlog;
use Mbsoft31\MbsoftBlog\Models\Post;
use Mbsoft31\MbsoftBlog\Models\User;

it('can create new untitled post', function () {

    $user = User::factory()->count(1)->create()->last();

    $post = MbsoftBlog::createNewPost($user);

    expect(true)->toBeTrue();
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

    expect(true)->toBeTrue();
    expect($post->title)->toBe('Untitled post');
    expect($post->slug)->toBe(Str::slug('Untitled post 2', '_'));
    expect($post->published)->toBeFalse();
    expect($post->user->id)->toBe($user->id);
});

it('can update existing post', function () {

    $user = User::factory()->count(1)->create()->last();
    $post = MbsoftBlog::createNewPost($user);
    $data = [
        'title' => 'hello blog',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam fugiat iure provident saepe voluptate. Aliquam amet doloremque fugiat ipsum iusto laboriosam maiores maxime, nam repellat rerum. Accusamus deleniti labore magnam natus sint ullam velit! Assumenda atque, delectus doloremque error facilis molestias necessitatibus neque nesciunt nihil nobis, nulla soluta suscipit voluptas?',
        'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci beatae dolor excepturi laboriosam laborum magni nemo quaerat quam quo veniam.',
    ];

    MbsoftBlog::updatePost($post, $data);

    expect(true)->toBeTrue();
    expect($post->title)->toBe('hello blog');
    expect($post->slug)->toBe(Str::slug('hello blog', '_'));
    expect($post->published)->toBeFalse();
    expect($post->user->id)->toBe($user->id);
    expect($post->description)->not()->toBeNull();
    expect($post->short_description)->not()->toBeNull();
});
