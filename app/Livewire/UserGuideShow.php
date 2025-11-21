<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserGuide;
use App\Services\UserGuide\UserGuideService;
use Illuminate\Support\Facades\Route;

class UserGuideShow extends Component
{
    public $guideId;
    public $guide;
    protected UserGuideService $userGuideService;

    public function boot(UserGuideService $userGuideService)
    {
        $this->userGuideService = $userGuideService;
    }

    public function mount($id)
    {
        $this->guideId = $id;
        $this->guide = $this->userGuideService->getByIdOrFail($id);
    }

    protected function getRouteDetails($routeNames)
    {
        $details = [];
        foreach ($routeNames as $name) {
            $route = Route::getRoutes()->getByName($name);
            if ($route) {
                $details[] = [
                    'name' => $name,
                    'uri' => $route->uri(),
                ];
            } else {
                $details[] = [
                    'name' => $name,
                    'uri' => null,
                ];
            }
        }
        return $details;
    }

    public function render()
    {
        $routeDetails = $this->guide && is_array($this->guide->routes)
            ? $this->getRouteDetails($this->guide->routes)
            : [];
        return view('livewire.user-guide-show', [
            'guide' => $this->guide,
            'routeDetails' => $routeDetails,
        ])->extends('layouts.master')->section('content');
    }
}
