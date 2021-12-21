<?php

namespace Mbsoft31\MbsoftBlog\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Mbsoft31\MbsoftBlog\MbsoftBlogServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Mbsoft31\\MbsoftBlog\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            MbsoftBlogServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');


        $migration = include __DIR__.'/../database/migrations/create_mbsoft-blog_table.php.stub';
        $migration->up();

    }
}
