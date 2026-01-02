<?php
namespace App\Interfaces;

interface  ICountriesRepository {
    public function getAllCountries();
    public function getCountrieById($id);
    public function getStates($phoneCode);
    public function getCountryByIso($iso);
}
