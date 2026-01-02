<?php

namespace App\Http\Controllers;

use App\Services\settingsManager;

class NotificationsController extends Controller
{
    public function __construct(private readonly settingsManager $settingsManager)
    {
    }

    public function index()
    {
        return datatables($this->settingsManager->getHistory())->make(true);
    }

}
