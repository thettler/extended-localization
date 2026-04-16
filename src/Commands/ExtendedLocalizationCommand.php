<?php

namespace Thettler\ExtendedLocalization\Commands;

use Illuminate\Console\Command;

class ExtendedLocalizationCommand extends Command
{
    public $signature = 'extended-localization';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
