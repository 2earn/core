<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SideBarMenu extends Component
{
    public $currentRouteName;

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();

    }
    public function render()
    {
        $adminTab = [
            'requests_commited_investors', 'requests_instructor', 'requests_identification',
            'orders_index',
            'surveys_index', 'surveys_create_update',
            'translate', 'translate_model_data',
            'shares_sold_dashboard', 'shares_sold_market_status', 'shares_sold_recent_transaction',
            'configuration_setting', 'configuration_amounts', 'configuration_ha',
            'role_index', 'role_assign',
            'items_index', 'target_create_update', 'countries_management', 'stat_countrie', 'user_list', 'balances_index',
            'business_sector_create_update', 'business_sector_index',
            'coupon_index', 'coupon_create',
            'platform_index', 'platform_create_update'
        ];

        $params = [
            'sidebarBusinessArray' => ['business_hub_trading', 'business_hub_additional_income', 'business_hub_be_influencer', 'business_hub_job_opportunities'],
            'sidebarBiographyArray' => ['biography_academic_background', 'biography_career_experience', 'biography_hard_skills', 'biography_soft_skills', 'biography_personal_characterization', 'biography_NCDPersonality', 'biography_sensory_representation_system', 'biography_MBTI', 'biography_e_business_card', 'biography_generating_pdf_report'],
            'sidebarArchiveArray' => ['surveys_archive', 'deals_archive'],
            'sidebarRoleArray' => ['role_index', 'role_assign'],
            'sidebarDashboardsArray' => ['configuration_setting', 'configuration_amounts', 'configuration_ha'],
            'sidebarShareSoldArray' => ['shares_sold_dashboard', 'shares_sold_market_status', 'shares_sold_recent_transaction'],
            'sidebarTranslateArray' => ['translate', 'translate_model_data'],
            'sidebarRequestsArray' => ['requests_commited_investors', 'requests_instructor', 'requests_identification'],
            'accountArray' => ['contact_number', 'account'],
            'businessSectorArray' => ['business_sector_create_update', 'business_sector_index'],
            'platformArray' => ['platform_index', 'platform_create_update'],
            'couponArray' => ['coupon_index', 'coupon_create'],
            'targetArray' => ['target_index', 'target_create_update'],
            'surveysArray' => ['surveys_index', 'surveys_create_update'],
            'contactArray' => ['contacts', 'user_contact_edit'],
            'orderArray' => ['orders_index'],
            'otherAdmin' => ['items_index', 'target_create_update', 'countries_management', 'stat_countrie', 'user_list', 'balances_index'],
            'sidebarSavingsArray'=>['savings_user_purchase','savings_recuperation_history'],
        'adminTab' => $adminTab

        ];
        return view('livewire.side-bar-menu', $params);
    }
}
