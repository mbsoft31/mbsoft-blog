<?php

namespace Mbsoft31\MbsoftBlog\Commands;

use Illuminate\Console\Command;

class MbsoftBlogCommand extends Command
{
    public $signature = 'mbsoft-blog';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
