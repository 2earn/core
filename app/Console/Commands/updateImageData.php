<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class updateImageData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update images data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = Storage::disk('public2')->allFiles('profiles');
        foreach ($files as $filePath) {
            $file = explode('/', $filePath);
            $file = explode('.', $file[1]);
            dump($file);

        }
        return Command::SUCCESS;
    }
}
