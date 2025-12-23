<?php

namespace App\View\Components;

use App\Models\UserGuide;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class PageTitle extends Component
{


    public $bg;
    public $pageTitle;
    public $helpUrl;

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

    public function __construct($pageTitle)
    {
        $this->bg = "#464fed";

        if (Route::currentRouteName() == "user_balance_bfs") {
            $this->bg = '#bc34b6';
        } elseif (Route::currentRouteName() == "user_balance_db") {
            $this->bg = '#009fe3';
        }

        $this->pageTitle = $pageTitle;
        $currentRoute = Route::currentRouteName();
        $userGuide = UserGuide::whereJsonContains('routes', $currentRoute)->first();
        $this->helpUrl = $userGuide ? route('user_guides_show', ['id' => $userGuide->id, 'locale' => app()->getLocale()]) : '#';

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
            'requests_identification',
            'requests_partner'
        ];
    }
    public function render()
    {
        return view('components.page-title');
    }
}
