<?php

namespace Hypersender\Hypersender\Commands;

use Illuminate\Console\Command;

class HypersenderCommand extends Command
{
    public $signature = 'hypersender-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
