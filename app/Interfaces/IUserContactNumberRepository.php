<?php
namespace App\Interfaces;
interface  IUserContactNumberRepository
{
    public function getActifNumber($idUser);
    public function getIDNumber($idUser);

}
