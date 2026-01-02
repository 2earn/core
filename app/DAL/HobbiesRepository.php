<?php
namespace App\DAL;

use App\Interfaces\IHobbiesRepository;
use App\Models\hobbie;
use Illuminate\Support\Facades\DB;
class  HobbiesRepository implements IHobbiesRepository
{
    public function getAllHobbies()
    {
        return hobbie::all();
    }
}
