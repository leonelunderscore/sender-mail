<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get files imported and clean them';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Cleaning files');
        $files = Storage::disk('public')->files('files');
        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }
        $this->info('Done');
    }
}
