<?php
namespace Core\Interfaces;
interface  IUserContactNumberRepository
{
    public function getActifNumber($idUser);
    public function getIDNumber($idUser);

}
