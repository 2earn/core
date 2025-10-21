<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Route;
use Livewire\Component;

class Sitemenu extends Component
{
    public $currentRouteName;
    public $sidebarBusinessArray;
    public $sidebarSavingsArray;
    public $sidebarBiographyArray;
    public $sidebarArchiveArray;
    public $sidebarRoleArray;
    public $sidebarDashboardsArray;
    public $sidebarShareSoldArray;
    public $sidebarTranslateArray;
    public $sidebarRequestsArray;

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
        $this->sidebarBusinessArray = [
            'business_hub_trading',
            'business_hub_additional_income',
            'business_hub_be_influencer',
            'business_hub_job_opportunities'
        ];
        $this->sidebarSavingsArray = [
            'savings_user_purchase',
            'savings_recuperation_history'
        ];
        $this->sidebarBiographyArray = [
            'biography_academic_background',
            'biography_career_experience',
            'biography_hard_skills',
            'biography_soft_skills',
            'biography_personal_characterization',
            'biography_NCDPersonality',
            'biography_sensory_representation_system',
            'biography_MBTI',
            'biography_e_business_card',
            'biography_generating_pdf_report'
        ];
        $this->sidebarArchiveArray = [
            'surveys_archive',
            'deals_archive'
        ];
        $this->sidebarRoleArray = [
            'role_index',
            'role_assign'
        ];
        $this->sidebarDashboardsArray = [
            'configuration_setting',
            'configuration_amounts',
            'configuration_ha'
        ];
        $this->sidebarShareSoldArray = [
            'shares_sold_dashboard',
            'shares_sold_market_status',
            'shares_sold_recent_transaction'
        ];
        $this->sidebarTranslateArray = [
            'translate',
            'translate_model_data'
        ];
        $this->sidebarRequestsArray = [
            'requests_commited_investors',
            'requests_instructor',
            'requests_identification'
        ];
    }

    public function render()
    {
        return view('livewire.sitemenu')->extends('layouts.master')->section('content');
    }
}

