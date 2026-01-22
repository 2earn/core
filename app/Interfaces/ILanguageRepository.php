<?php
namespace App\Interfaces;

use App\Models\language;

interface  ILanguageRepository {
    public function getAllLanguage();

    public function getAllLanguage2();
    public  function addLanguage(language $language);

    public function getLanguageById($id);


}
