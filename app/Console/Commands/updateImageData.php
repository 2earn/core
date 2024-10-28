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
    protected $resultSet = [
        Image::IMAGE_PREFIX_PROFILE => ['ALL' => 0, 'Filled' => 0, 'NOT Filled' => 0],
        Image::IMAGE_PREFIX_FRONT => ['ALL' => 0, 'Filled' => 0, 'NOT Filled' => 0],
        Image::IMAGE_PREFIX_BACK => ['ALL' => 0, 'Filled' => 0, 'NOT Filled' => 0],
        Image::IMAGE_PREFIX_INTERNATIONAL => ['ALL' => 0, 'Filled' => 0, 'NOT Filled' => 0]
    ];


    public function handle()
    {
        DB::table('images')->truncate();

        $files = Storage::disk('public2')->allFiles('profiles');

        foreach ($files as $filePath) {
            $file = explode('/', $filePath);
            $filenameWithExt = $file[1];
            $file = explode('.', $filenameWithExt);
            $filename = $file[0];
            $fileExtenssion = $file[1];


            if (str_starts_with($filename, Image::IMAGE_PREFIX_PROFILE)) {
                $this->resultSet[Image::IMAGE_PREFIX_PROFILE]['ALL']++;
                $idUser = substr($filename, strlen(Image::IMAGE_PREFIX_PROFILE), strlen($filename));

                $user = User::where('idUser', $idUser)->first();
                if (!is_null($user)) {
                    $image = Image::create([
                        'type' => User::IMAGE_TYPE_PROFILE,
                        'url' => Image::IMAGE_PROFILE_PATH . Image::IMAGE_PREFIX_PROFILE . $idUser . '.' . $fileExtenssion
                    ]);
                    $user->profileImage()->save($image);
                    $this->output->writeln('------> Filled : ' . $image->url, false);
                    $this->resultSet[Image::IMAGE_PREFIX_PROFILE]['Filled']++;
                } else {
                    $this->output->writeln($idUser . ' ====> Not Filled : ' . $filename . false);
                    $this->resultSet[Image::IMAGE_PREFIX_PROFILE]['NOT Filled']++;
                }

            }
            if (str_starts_with($filename, Image::IMAGE_PREFIX_FRONT)) {
                $this->resultSet[Image::IMAGE_PREFIX_FRONT]['ALL']++;
                $idUser = substr($filename, strlen(Image::IMAGE_PREFIX_FRONT), strlen($filename));
                $user = User::where('idUser', $idUser)->first();
                if (!is_null($user)) {
                    $image = Image::create([
                        'type' => User::IMAGE_TYPE_NATIONAL_FRONT,
                        'url' => Image::IMAGE_PROFILE_PATH . Image::IMAGE_PREFIX_FRONT . $idUser . '.' . $fileExtenssion
                    ]);
                    $user->nationalIdentitieFrontImage()->save($image);
                    $this->output->writeln('------> Filled : ' . $image->url, false);
                    $this->resultSet[Image::IMAGE_PREFIX_FRONT]['Filled']++;
                } else {
                    $this->output->writeln($idUser . ' ====> Not Filled : ' . $filename . false);
                    $this->resultSet[Image::IMAGE_PREFIX_FRONT]['NOT Filled']++;
                }
            }
            if (str_starts_with($filename, Image::IMAGE_PREFIX_BACK)) {
                $this->resultSet[Image::IMAGE_PREFIX_BACK]['ALL']++;
                $idUser = substr($filename, strlen(Image::IMAGE_PREFIX_BACK), strlen($filename));
                $user = User::where('idUser', $idUser)->first();
                if (!is_null($user)) {
                    $image = Image::create([
                        'type' => User::IMAGE_TYPE_NATIONAL_BACK,
                        'url' => Image::IMAGE_PROFILE_PATH . Image::IMAGE_PREFIX_BACK . $idUser . '.' . $fileExtenssion
                    ]);
                    $user->nationalIdentitieBackImage()->save($image);
                    $this->output->writeln('------> Filled : ' . $image->url, false);
                    $this->resultSet[Image::IMAGE_PREFIX_BACK]['Filled']++;
                } else {
                    $this->output->writeln($idUser . ' ====> Not Filled : ' . $filename . false);
                    $this->resultSet[Image::IMAGE_PREFIX_BACK]['NOT Filled']++;
                }
            }
            if (str_starts_with($filename, Image::IMAGE_PREFIX_INTERNATIONAL)) {
                $this->resultSet[Image::IMAGE_PREFIX_INTERNATIONAL]['ALL']++;
                $idUser = substr($filename, strlen(Image::IMAGE_PREFIX_INTERNATIONAL), strlen($filename));
                $user = User::where('idUser', $idUser)->first();
                if (!is_null($user)) {
                    $image = Image::create([
                        'type' => User::IMAGE_TYPE_INTERNATIONAL,
                        'url' => Image::IMAGE_PROFILE_PATH . Image::IMAGE_PREFIX_INTERNATIONAL . $idUser . '.' . $fileExtenssion
                    ]);
                    $user->internationalIdentitieImage()->save($image);
                    $this->output->writeln('------> Filled : ' . $image->url, false);
                    $this->resultSet[Image::IMAGE_PREFIX_INTERNATIONAL]['Filled']++;
                } else {
                    $this->output->writeln($idUser . ' ====> Not Filled : ' . $filename . false);
                    $this->resultSet[Image::IMAGE_PREFIX_INTERNATIONAL]['NOT Filled']++;
                }


            }

        }
        $this->output->writeln(json_encode($this->resultSet), false);
        return Command::SUCCESS;

    }
}
