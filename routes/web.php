<?php

use App\Livewire\AcceptFinancialRequest;
use App\Livewire\Account;
use App\Livewire\ChangePassword;
use App\Livewire\CheckOptCode;
use App\Livewire\ConfigurationHA;
use App\Livewire\ContactNumber;
use App\Livewire\Contacts;
use App\Livewire\Description;
use App\Livewire\EditUserContact;
use App\Livewire\EntretienArbre;
use App\Livewire\EvolutionArbre;
use App\Livewire\FinancialTransaction;
use App\Livewire\ForgotPassword;
use App\Livewire\HistoriqueRecuperation;
use App\Livewire\Hobbies;
use App\Livewire\Home;
use App\Livewire\IdentificationRequest;
use App\Livewire\Login;
use App\Livewire\NotificationHistory;
use App\Livewire\NotificationSettings;
use App\Livewire\Registre;
use App\Livewire\RequestPublicUser;
use App\Livewire\StripView;
use App\Livewire\TranslateView;
use App\Livewire\UserBalanceBFS;
use App\Livewire\UserBalanceCB;
use App\Livewire\UserBalanceDB;
use App\Livewire\UserBalanceSMS;
use App\Livewire\UserPurchaseHistory;
use App\Livewire\ValidateAccount;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/pdf', function () {
    $pdf = Pdf::loadView('pdf');
    Mail::send('pwd_email', ['data' => "azerty"], function ($message) use ($pdf) {
        $message->to('khalil@2earn.cash')->subject('Invoice');
        $message->attachData($pdf->output(), "invoice.pdf");
    });
});

Route::get('/tables-datatables', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z]{2}'], 'middleware' => 'setlocale'], function () {
    Route::get('/coming-soon-Move', [App\Http\Controllers\HomeController::class, 'index'])->name('coming_move');
    Route::get('/coming-soon-learn', [App\Http\Controllers\HomeController::class, 'index'])->name('coming_learn');
    Route::get('/coming-soon-shop', [App\Http\Controllers\HomeController::class, 'index'])->name('coming_shop');
});

Route::get('/widgets', [App\Http\Controllers\HomeController::class, 'index'])->name('widgets');
Route::get('/offline', function () {
    return view('livewire.offline');
});

Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z]{2}'], 'middleware' => 'setlocale'], function () {
    Route::get('/privacy', \App\Livewire\Privacy::class)->name('privacy');
    Route::get('/who-we-are', \App\Livewire\WhoWeAre::class)->name('who_we_are');
    Route::get('/general-terms-of-use', \App\Livewire\GeneralTermsOfUse::class)->name('general_terms_of_use');
    Route::get('/contact-us', \App\Livewire\ContactUs::class)->name('contact_us');
});

Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z]{2}'], 'middleware' => 'setlocale'], function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/', Home::class)->name('main');
        Route::get('/home', Home::class)->name('home');
        Route::get('/account', Account::class)->name('account');
        Route::get('/contacts', Contacts::class)->name('contacts');
        Route::get('/notification/history', NotificationHistory::class)->name('notification_history');
        Route::get('/notification/settings', NotificationSettings::class)->name('notification_settings');

        Route::prefix('/business-hub')->name('business_hub_')->group(function () {
            Route::get('/trading', \App\Livewire\Trading::class)->name('trading');
            Route::get('/additional-income', \App\Livewire\AdditionalIncome::class)->name('additional_income');
            Route::get('/be-influencer', \App\Livewire\BeInfluencer::class)->name('be_influencer');
            Route::get('/job/opportunities', \App\Livewire\JobOpportunities::class)->name('job_opportunities');
        });

        Route::prefix('/be-influencer')->name('be_influencer_')->group(function () {
            Route::get('/tree/evolution', EvolutionArbre::class)->name('tree_evolution');
            Route::get('/tree/maintenance', EntretienArbre::class)->name('tree_maintenance');
            Route::get('/successful-sharing-pool', \App\Livewire\SuccessfulSharingPool::class)->name('successful_sharing_pool');
        });

        Route::prefix('/savings')->name('savings_')->group(function () {
            Route::get('/recuperation/history', HistoriqueRecuperation::class)->name('recuperation_history');
            Route::get('/user/purchase', UserPurchaseHistory::class)->name('user_purchase');
        });

        Route::prefix('/biography')->name('biography_')->group(function () {
            Route::get('/academic-background', \App\Livewire\AcademicBackground::class)->name('academic_background');
            Route::get('/career-experience', \App\Livewire\CareerExperience::class)->name('career_experience');
            Route::get('/hard-skills', \App\Livewire\HardSkills::class)->name('hard_skills');
            Route::get('/soft-skills', \App\Livewire\SoftSkills::class)->name('soft_skills');
            Route::get('/personal-characterization', \App\Livewire\PersonalCharacterization::class)->name('personal_characterization');
            Route::get('/NCDPersonality', \App\Livewire\CDPersonality::class)->name('NCDPersonality');
            Route::get('/sensory-representation-system', \App\Livewire\SensoryRepresentationSystem::class)->name('sensory_representation_system');
            Route::get('/MBTI', \App\Livewire\MBTI::class)->name('MBTI');
            Route::get('/e-business-card', \App\Livewire\EBusinessCard::class)->name('e_business_card');
            Route::get('/generating/pdf/report', \App\Livewire\GeneratingPDFReport::class)->name('generating_pdf_report');
        });

        Route::get('/treeview', \App\Livewire\Treeview::class)->name('treeview');

        Route::prefix('/user')->group(function () {
            Route::get('/balance-sms', UserBalanceSMS::class)->name('user_balance_sms');
            Route::get('/balance-cb', UserBalanceCB::class)->name('user_balance_cb');
            Route::get('/balance-db', UserBalanceDB::class)->name('user_balance_db');
            Route::get('/balance-bfs', UserBalanceBFS::class)->name('user_balance_bfs');
            Route::get('/balance-tree', \App\Livewire\UserBalanceTree::class)->name('user_balance_tree');
            Route::get('/balance-chance', \App\Livewire\UserBalanceChance::class)->name('user_balance_chance');
        });

        Route::get('/financial/transaction', FinancialTransaction::class)->name('financial_transaction');
        Route::get('/contact-number', ContactNumber::class)->name('contact_number');
        Route::get('/user/edit-contact', EditUserContact::class)->name('user_contact_edit');
        Route::get('/balances/exchange/funding/RequestPulicUser', RequestPublicUser::class)->name('user_request_public');
        Route::get('/balances/exchange/funding/strip', stripView::class)->name('payment_strip');
        Route::get('/paytabs', '\App\Livewire\Pay@test')->name('paytabs');
        Route::get('/hobbies', Hobbies::class)->name('hobbies');

        Route::prefix('/orders')->name('orders_')->group(function () {
            Route::get('/index', \App\Livewire\OrdersIndex::class)->name('index');
            Route::get('/previous', \App\Livewire\OrdersPrevious::class)->name('previous');
            Route::get('/{id}/detail', \App\Livewire\OrderItem::class)->name('detail');
            Route::get('/summary', \App\Livewire\OrderSummary::class)->name('summary');
        });
        Route::prefix('/items')->name('items_')->group(function () {
            Route::get('/index', \App\Livewire\ItemsIndex::class)->name('index');
            Route::get('/{id}/detail', \App\Livewire\ItemsDetails::class)->name('detail');
            Route::get('/', \App\Livewire\ItemsCreateUpdate::class)->name('create_update');
            Route::get('/{platformId}/', \App\Livewire\ItemsCreateUpdate::class)->name('platform_create_update');
        });

        Route::prefix('/news')->name('news_')->group(function () {
            Route::get('/index', \App\Livewire\NewsIndex::class)->name('index');
            Route::get('/', \App\Livewire\NewsCreateUpdate::class)->name('create_update');
        });

        Route::get('/accept/request', AcceptFinancialRequest::class)->name('accept_financial_request')->middleware('CloseAuth');

        Route::prefix('/surveys')->name('surveys_')->group(function () {
            Route::middleware(['IsSuperAdmin'])->group(function () {
                Route::get('/index', \App\Livewire\SurveyIndex::class)->name('index');
            });
            Route::get('/archive', \App\Livewire\SurveyArchive::class)->name('archive');
            Route::get('/', \App\Livewire\SurveyCreateUpdate::class)->name('create_update');
            Route::get('/show/{idSurvey}', \App\Livewire\SurveyShow::class)->name('show');
            Route::get('/participate/{idSurvey}', \App\Livewire\SurveyParicipate::class)->name('participate');
            Route::get('/results/{idSurvey}', \App\Livewire\SurveyResult::class)->name('results');
            Route::get('/{idSurvey}/question', \App\Livewire\SurveyQuestionCreateUpdate::class)->name('question_create_update');
            Route::get('/{idSurvey}/question/{idQuestion}/Choice', \App\Livewire\SurveyQuestionChoiceCreateUpdate::class)->name('question_choice_create_update');
        });

        Route::prefix('/deals')->name('deals_')->group(function () {
            Route::get('/index', \App\Livewire\DealsIndex::class)->name('index');
            Route::middleware(['IsSuperAdmin'])->group(function () {
                Route::get('/{idPlatform}/UpdateCreate', \App\Livewire\DealsCreateUpdate::class)->name('create_update');
            });
            Route::get('/{id}/show', \App\Livewire\DealsShow::class)->name('show');
            Route::get('/archive', \App\Livewire\DealsArchive::class)->name('archive');
        });

        Route::prefix('/faq')->name('faq_')->group(function () {
            Route::get('/index', \App\Livewire\FaqIndex::class)->name('index');
        });

        Route::prefix('/coupon')->name('coupon_')->group(function () {
            Route::get('/history', \App\Livewire\CouponHistory::class)->name('history');
        });

        // SUPER ADMIN MENU
        // -----------------------------------------------------------

        Route::middleware(['IsSuperAdmin'])->group(function () {
            Route::get('/user/list', \App\Livewire\UsersList::class)->name('user_list');
            Route::get('/user/{idUser}/details', \App\Livewire\UserDetails::class)->name('user_details');

            Route::prefix('/configuration')->group(function () {
                Route::get('/ha', ConfigurationHA::class)->name('configuration_ha');
                Route::get('/setting', \App\Livewire\ConfigurationSetting::class)->name('configuration_setting');
                Route::get('/amounts', \App\Livewire\ConfigurationAmounts::class)->name('configuration_amounts');
            });

            Route::get('/countries/management', \App\Livewire\CountriesManagement::class)->name('countries_management');

            Route::prefix('/requests')->group(function () {
                Route::get('/identification', identificationRequest::class)->name('requests_identification');
                Route::get('/commited-investors', \App\Livewire\CommitedRequest::class)->name('requests_commited_investors');
                Route::get('/commited-investors/{id}/show', \App\Livewire\CommitedRequestShow::class)->name('requests_commited_investors_show');
                Route::get('/instructor', \App\Livewire\InstructorRequest::class)->name('requests_instructor');
                Route::get('/instructor/{id}/show', \App\Livewire\InstructorRequestShow::class)->name('requests_instructor_show');
            });

            Route::prefix('/translate')->group(function () {
                Route::get('/', TranslateView::class)->name('translate');
                Route::get('/model/data', \App\Livewire\TranslateModelData::class)->name('translate_model_data');
                Route::get('/{id}/html/{lang}', \App\Livewire\TranslationHtmlEditor::class)->name('translate_html');
            });

            Route::prefix('/target')->name('target_')->group(function () {
                Route::get('/index', \App\Livewire\TargetIndex::class)->name('index');
                Route::get('/', \App\Livewire\TargetCreateUpdate::class)->name('create_update');
                Route::get('/show/{idTarget}', \App\Livewire\TargetShow::class)->name('show');
                Route::get('/{idTarget}/group', \App\Livewire\GroupCreateUpdate::class)->name('group_create_update');
                Route::get('/{idTarget}/condition', \App\Livewire\ConditionCreateUpdate::class)->name('condition_create_update');
            });

            Route::prefix('/platform')->name('platform_')->group(function () {
                Route::get('/index', \App\Livewire\PlatformIndex::class)->name('index');
                Route::get('/', \App\Livewire\PlatformCreateUpdate::class)->name('create_update');
                Route::get('/{id}', \App\Livewire\PlatformShow::class)->name('show');
                Route::get('/{userId}/promotion', \App\Livewire\PlatformPromotion::class)->name('promotion');
            });

            Route::prefix('/role')->name('role_')->group(function () {
                Route::get('/index', \App\Livewire\RoleIndex::class)->name('index');
                Route::get('/', \App\Livewire\RoleCreateUpdate::class)->name('create_update');
                Route::get('/assign', \App\Livewire\EditAdmin::class)->name('assign');
            });

            Route::prefix('/balances')->name('balances_')->group(function () {
                Route::get('/index', \App\Livewire\Balances::class)->name('index');
            });

            Route::get('/index/test', \App\Livewire\NewBalance::class)->name('index_test');

            Route::prefix('/faq')->name('faq_')->group(function () {
                Route::get('/', \App\Livewire\FaqCreateUpdate::class)->name('create_update');
            });

            Route::get('/stat/countrie', \App\Livewire\StatCountrie::class)->name('stat_countrie');
            Route::prefix('/shares-sold')->name('shares_sold_')->group(function () {
                Route::get('dashboard', \App\Livewire\SharesSold::class)->name('dashboard');
                Route::get('/market-status', \App\Livewire\SharesSoldMarketStatus::class)->name('market_status');
                Route::get('/recent-transaction', \App\Livewire\SharesSoldRecentTransaction::class)->name('recent_transaction');
            });

            Route::prefix('/business/sector')->name('business_sector_')->group(function () {
                Route::get('/index', \App\Livewire\BusinessSectorIndex::class)->name('index');
                Route::get('/', \App\Livewire\BusinessSectorCreateUpdate::class)->name('create_update');
            });

            Route::prefix('/coupon')->name('coupon_')->group(function () {
                Route::get('/index', \App\Livewire\CouponIndex::class)->name('index');
                Route::get('/', \App\Livewire\CouponCreate::class)->name('create');
            });
            Route::prefix('/coupon/injector')->name('coupon_injector_')->group(function () {
                Route::get('/index', \App\Livewire\CouponInjectorIndex::class)->name('index');
                Route::get('/', \App\Livewire\CouponInjectorCreate::class)->name('create');
            });

        });

        Route::prefix('/settlement')->name('settlement_')->group(function () {
            Route::get('/tracking', \App\Livewire\SettlementTracking::class)->name('tracking');
        });

        Route::prefix('/coupon')->name('coupon_')->group(function () {
            Route::get('/{id}/buy', \App\Livewire\CouponBuy::class)->name('buy');
        });

        Route::prefix('/coupon/injector')->name('coupon_injector_')->group(function () {
            Route::get('/runner', \App\Livewire\CouponInjectorRunner::class)->name('runner');
        });

        Route::prefix('/sales')->name('sales_')->group(function () {
            Route::get('/{id}/tracking', \App\Livewire\SalesTracking::class)->name('tracking');
        });

        Route::get('/shares/solde', \App\Livewire\SharesSolde::class)->name('shares_solde');

        Route::get('/stat-countries', 'App\Http\Controllers\ApiController@getCountriStat')->name('api_stat_countries');
        Route::post('/validate-phone', 'App\Http\Controllers\ApiController@validatePhone')->name('validate_phone');
        Route::post('/buy-action', 'App\Http\Controllers\ApiController@buyAction')->name('buy_action');
        Route::get('/action-by-ammount', 'App\Http\Controllers\ApiController@actionByAmmount')->name('action_by_ammount');
        Route::post('/gift-action-by-ammount', 'App\Http\Controllers\ApiController@giftActionByAmmount')->name('gift_action_by_ammount');
    });

    Route::prefix('/business/sector')->name('business_sector_')->group(function () {
        Route::get('/{id}/show', \App\Livewire\BusinessSectorShow::class)->name('show');
    });

    Route::get('/changePassword/{idUser}', ChangePassword::class)->name('reset_password');
    Route::get('/users/list', 'App\Http\Controllers\ApiController@getUsersList')->name('api_users_list');
    Route::get('/login', Login::class)->name('login')->middleware('setLocalLogin');
    Route::get('/registre', Registre::class)->name('registre');
    Route::get('/forget-password', ForgotPassword::class)->name('forget_password');
    Route::get('/check-opt-code/{iduser}/{ccode}/{numTel}', CheckOptCode::class)->name('check_opt_code');
    Route::get('/validate-account', ValidateAccount::class)->name('validate_account');

    Route::group(['prefix' => 'api/v1'], function () {
        Route::get('/countries', 'App\Http\Controllers\ApiController@getCountries')->name('api_countries');
        Route::get('/settings', 'App\Http\Controllers\ApiController@getSettings')->name('api_settings');
        Route::get('/balance/operations', 'App\Http\Controllers\ApiController@getBalanceOperations')->name('api_bal_operations');
        Route::get('/amounts', 'App\Http\Controllers\ApiController@getAmounts')->name('api_Amounts');
        Route::get('/action/historys', 'App\Http\Controllers\ApiController@getActionHistorys')->name('api_action_history');
        Route::get('/user/contacts', 'App\Http\Controllers\ApiController@getUserContacts')->name('api_user_contacts');
        Route::get('/user-balances/{idAmounts}', 'App\Http\Controllers\ApiController@getUserBalances')->name('api_user_balances');
        Route::get('/user-balances-list/{idUser}/{idAmounts}', 'App\Http\Controllers\ApiController@getUserBalancesList')->name('api_user_balances_list');
        Route::get('/shares-solde-list/{idUser}', 'App\Http\Controllers\ApiController@getSharesSoldeList')->name('api_shares_solde_list');
        Route::get('/user/admin', 'App\Http\Controllers\ApiController@getUserAdmin')->name('api_user_admin');
        Route::get('/history/notification', 'App\Http\Controllers\ApiController@getHistoryNotification')->name('api_history_notification');
        Route::get('/coupons', 'App\Http\Controllers\ApiController@getCoupons')->name('api_coupon');
        Route::get('/coupons/injector', 'App\Http\Controllers\ApiController@getCouponsInjector')->name('api_coupon_injector');
        Route::get('/user/coupons', 'App\Http\Controllers\ApiController@getUserCouponsInjector')->name('api_user_coupon_injector');
        Route::get('/coupons/user', 'App\Http\Controllers\ApiController@getUserCoupons')->name('api_user_coupon');
        Route::get('/platforms', 'App\Http\Controllers\ApiController@getPlatforms')->name('api_platforms');
        Route::get('/roles', 'App\Http\Controllers\ApiController@getRoles')->name('api_role');
        Route::get('/deals/search', [\App\Livewire\DealsIndex::class, 'filterDeals'])->name('api_deal_search');
        Route::get('/request', 'App\Http\Controllers\ApiController@getRequest')->name('api_request');
        Route::get('/representatives', 'App\Http\Controllers\ApiController@getRepresentatives')->name('api_representatives');
        Route::get('/user/balancesCB', 'App\Http\Controllers\ApiController@getUserBalancesCB')->name('api_user_balances_cb');
        Route::get('/user/invitations', 'App\Http\Controllers\ApiController@getInvitationsUser')->name('api_user_invitations');
        Route::get('/user/purchaseBFS/{type}', 'App\Http\Controllers\ApiController@getPurchaseBFSUser')->name('api_user_bfs_purchase');
        Route::get('/user/tree', 'App\Http\Controllers\ApiController@getTreeUser')->name('api_user_tree');
        Route::get('/user/sms', 'App\Http\Controllers\ApiController@getSmsUser')->name('api_user_sms');
        Route::get('/user/chance', 'App\Http\Controllers\ApiController@getChanceUser')->name('api_user_chance');
        Route::post('/paytabs/notification', 'App\Http\Controllers\ApiController@handlePaymentNotification')->name('notification_from_paytabs')->withoutMiddleware('web');
        Route::get('/target/{idTarget}/data', [\App\Http\Controllers\TargetController::class, 'getTargetData'])->name('api_target_data');

        Route::get('sankey', 'App\Http\Controllers\ApiController@getSankey')->name('API_sankey');

        Route::get('/api/shares/solde', 'App\Http\Controllers\ApiController@getSharesSolde')->name('api_shares_solde');
        Route::get('/api/shares/soldes', 'App\Http\Controllers\ApiController@getSharesSoldes')->name('api_shares_soldes');
        Route::get('/api/transfert', 'App\Http\Controllers\ApiController@getTransfert')->name('api_transfert');
        Route::get('/api/user/cash', 'App\Http\Controllers\ApiController@getUserCashBalance')->name('api_user_cash');
        Route::get('/api/share/evolution', 'App\Http\Controllers\ApiController@getSharePriceEvolution')->name('api_share_evolution');
        Route::get('/api/share/evolution/date', 'App\Http\Controllers\ApiController@getSharePriceEvolutionDate')->name('api_share_evolution_date');
        Route::get('/api/share/evolution/week', 'App\Http\Controllers\ApiController@getSharePriceEvolutionWeek')->name('api_share_evolution_week');
        Route::get('/api/share/evolution/month', 'App\Http\Controllers\ApiController@getSharePriceEvolutionMonth')->name('api_share_evolution_month');
        Route::get('/api/share/evolution/day', 'App\Http\Controllers\ApiController@getSharePriceEvolutionDay')->name('api_share_evolution_day');
        Route::get('/api/share/evolution/user', 'App\Http\Controllers\ApiController@getSharePriceEvolutionUser')->name('api_share_evolution_user');

        Route::get('/api/action/values', 'App\Http\Controllers\ApiController@getActionValues')->name('api_action_values');
        Route::post('/api/coupon/delete', 'App\Http\Controllers\ApiController@deleteCoupon')->name('api_delete_coupons');

        Route::get('/get-updated-card-content', 'App\Http\Controllers\ApiController@getUpdatedCardContent')->name('get-updated-card-content');
        Route::post('/add-cash', 'App\Http\Controllers\ApiController@addCash')->name('add_cash');
        Route::post('/vip', 'App\Http\Controllers\ApiController@vip')->name('vip');
        Route::post('/send-sms', 'App\Http\Controllers\ApiController@sendSMS')->name('send_sms');
        Route::post('/update-balance-status', 'App\Http\Controllers\ApiController@updateBalanceStatus')->name('update-balance-status');
        Route::post('/update-reserve-date', 'App\Http\Controllers\ApiController@updateReserveDate')->name('update-reserve-date');
        Route::post('/update-balance-real', 'App\Http\Controllers\ApiController@updateBalanceReal')->name('update-balance-real');

    });

});

Route::get('/reset-not', 'App\Http\Controllers\FinancialRequestController@resetInComingNotification')->name('reset_incoming_notification');
Route::get('/reset-not-out', 'App\Http\Controllers\FinancialRequestController@resetOutGoingNotification')->name('reset_out_going_notification');
Route::get('/', function () {
    return redirect(app()->getLocale());
});

Route::get('/store-form', 'App\Http\Controllers\PostController@store')->name('save_ph');
Route::get('/mail-verif', 'App\Http\Controllers\PostController@verifyMail')->name('mailVerif');
Route::get('/mail-verif-Opt', 'App\Http\Controllers\PostController@mailVerifOpt')->name('mail_verif_opt');
Route::get('/mail-verif-New', 'App\Http\Controllers\PostController@mailVerifNew')->name('mail_verif_New');

Route::get('/send-mail-notification', 'App\Http\Controllers\PostController@sendMail')->name('sendMail');
Route::get('/members', 'App\Http\Controllers\PostController@getMember')->name('members');

Route::get('/get-request-ajax', 'App\Http\Controllers\ApiController@getRequestAjax')->name('get_request_ajax');
Route::get('/logout-sso', 'App\Http\Controllers\ApiController@logoutSSo')->name('logout_sso')->middleware('auth:api');
Route::view('/tests', 'tests');

Route::get('/', function () {
    return redirect(app()->getLocale());
});

Route::get('/{slug}', function () {
    return redirect(app()->getLocale());
});

Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z]{2}'], 'middleware' => 'setlocale'], function () {
    Route::get('/{slug}', function () {
        throw new  NotFoundHttpException();
    });
});


