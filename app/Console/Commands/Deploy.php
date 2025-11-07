<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Deploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'run setup for new env';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call('migrate', ['--force' => true]);
    }
}
