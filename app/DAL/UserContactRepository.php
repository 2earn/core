<?php
namespace App\DAL;
use App\Interfaces\IUserContactRepository;
use Illuminate\Support\Facades\DB;

class  UserContactRepository implements IUserContactRepository
{
    public function getAllUserContacts()
    {
        return DB::table('user_contacts')->get();
    }
}
