<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class StorePublicKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:store-public-key {key-content}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store the OAuth public key in the storage directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keyContent = $this->argument('key-content');

        if (!str_contains($keyContent, '-----BEGIN PUBLIC KEY-----')) {
            $this->error('Invalid public key format. Must be in PEM format.');
            return 1;
        }

        $keyPath = config('services.auth_2earn.public_key_path');

        if (!$keyPath) {
            $this->error('PUBLIC_KEY_PATH not configured in .env file.');
            return 1;
        }

        $directory = dirname($keyPath);
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
            $this->info("Created directory: storage/app/{$directory}");
        }

        Storage::put($keyPath, $keyContent);

        $fullPath = storage_path('app/' . $keyPath);
        $this->info("Public key successfully stored at: {$fullPath}");

        return 0;
    }
}
