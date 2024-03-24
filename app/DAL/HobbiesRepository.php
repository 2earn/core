<?php
namespace App\DAL;

use Core\Interfaces\IHobbiesRepository;
use Core\Models\hobbie;
use Illuminate\Support\Facades\DB;
class  HobbiesRepository implements IHobbiesRepository
{
    public function getAllHobbies()
    {
        return hobbie::all();
    }
}
