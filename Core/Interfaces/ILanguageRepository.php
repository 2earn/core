<?php
namespace Core\Interfaces;

use Core\Models\language;

interface  ILanguageRepository {
    public function getAllLanguage();

    public function getAllLanguage2();
    public  function addLanguage(language $language);

    public function getLanguageById($id);


}
