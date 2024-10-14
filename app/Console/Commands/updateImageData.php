<?php

namespace App\Console\Commands;

use App\Models\Image;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
        DB::table('images')->truncate();
        $files = Storage::disk('public2')->allFiles('profiles');
        foreach ($files as $filePath) {
            $file = explode('/', $filePath);
            $filename = $file[1];
            $file = explode('.', $file[1]);
            $fileExtenssion = $file[1];
            $this->output->writeln($filename . ' ' . $fileExtenssion, false);

            if (str_starts_with($file[0], 'profile-image')) {
                $idUser = substr($file[0], strlen('profile-image') + 1, strlen($file[0]) + 1);
                $this->output->writeln(' User ID : ' . $idUser, false);
                $user = User::where('idUser', $idUser)->first();
                if (!is_null($user)) {
                    $image = Image::create([
                        'type' => User::IMAGE_TYPE_PROFILE,
                        'url' => 'uploads/profiles/profile-image-' . $idUser . '.' . $fileExtenssion
                    ]);
                    $user->profileImage()->save($image);
                    $this->output->writeln('___________________________________ Filled : ' . json_encode($image), false);
                }

            }

            if (str_starts_with($file[0], 'front-id-image')) {
                $idUser = substr($file[0], strlen('front-id-image'), strlen($file[0]) + 1);
                $this->output->writeln(' User ID : ' . $idUser, false);

                $user = User::where('idUser', $idUser)->first();
                if (!is_null($user)) {
                    $image = Image::create([
                        'type' => User::IMAGE_TYPE_NATIONAL_FRONT,
                        'url' => 'uploads/profiles/front-id-image' . $idUser . '.' . $fileExtenssion
                    ]);
                    $user->nationalIdentitieFrontImage()->save($image);
                    $this->output->writeln(' Filled : ' . json_encode($image), false);
                }
            }

            if (str_starts_with($file[0], 'back-id-image')) {
                $idUser = substr($file[0], strlen('back-id-image') , strlen($file[0]) + 1);
                $this->output->writeln(' User ID : ' . $idUser, false);

                $user = User::where('idUser', $idUser)->first();
                if (!is_null($user)) {
                    $image = Image::create([
                        'type' => User::IMAGE_TYPE_NATIONAL_BACK,
                        'url' => 'uploads/profiles/back-id-image' . $idUser . '.' . $fileExtenssion
                    ]);
                    $user->nationalIdentitieBackImage()->save($image);
                    $this->output->writeln(' Filled : ' . json_encode($image), false);
                }
            }

            if (str_starts_with($file[0], 'international-id-image')) {
                $idUser = substr($file[0], strlen('international-id-image'), strlen($file[0]) + 1);
                $this->output->writeln(' User ID : ' . $idUser, false);

                $user = User::where('idUser', $idUser)->first();
                if (!is_null($user)) {
                    $image = Image::create([
                        'type' => User::IMAGE_TYPE_INTERNATIONAL,
                        'url' => 'uploads/profiles/profile-image-' . $idUser . '.' . $fileExtenssion
                    ]);
                    $user->internationalIdentitieImage()->save($image);
                    $this->output->writeln(' Filled : ' . json_encode($image), false);
                }
            }

        }
        return Command::SUCCESS;
    }
}
