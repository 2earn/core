<?php

use App\Http\Livewire\AcceptFinancialRequest;
use App\Http\Livewire\Account;
use App\Http\Livewire\ChangePassword;
use App\Http\Livewire\CheckOptCode;
use App\Http\Livewire\ConfigurationHA;
use App\Http\Livewire\ContactNumber;
use App\Http\Livewire\Contacts;
use App\Http\Livewire\Description;
use App\Http\Livewire\EditUserContact;
use App\Http\Livewire\EntretienArbre;
use App\Http\Livewire\EvolutionArbre;
use App\Http\Livewire\FinancialTransaction;
use App\Http\Livewire\ForgotPassword;
use App\Http\Livewire\HistoriqueRecuperation;
use App\Http\Livewire\Hobbies;
use App\Http\Livewire\Home;
use App\Http\Livewire\IdentificationRequest;
use App\Http\Livewire\Login;
use App\Http\Livewire\NotificationHistory;
use App\Http\Livewire\NotificationSettings;
use App\Http\Livewire\Registre;
use App\Http\Livewire\RequestPublicUser;
use App\Http\Livewire\StripView;
use App\Http\Livewire\TranslateView;
use App\Http\Livewire\UserBalanceBFS;
use App\Http\Livewire\UserBalanceCB;
use App\Http\Livewire\UserBalanceDB;
use App\Http\Livewire\UserBalanceSMS;
use App\Http\Livewire\UserPurchaseHistory;
use App\Http\Livewire\ValidateAccount;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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
    Route::get('/privacy', \App\Http\Livewire\Privacy::class)->name('privacy');
    Route::get('/who-we-are', \App\Http\Livewire\WhoWeAre::class)->name('who_we_are');
    Route::get('/general-terms-of-use', \App\Http\Livewire\GeneralTermsOfUse::class)->name('general_terms_of_use');
    Route::get('/contact-us', \App\Http\Livewire\ContactUs::class)->name('contact_us');
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
            Route::get('/trading', \App\Http\Livewire\Trading::class)->name('trading');
            Route::get('/additional-income', \App\Http\Livewire\AdditionalIncome::class)->name('additional_income');
            Route::get('/be-influencer', \App\Http\Livewire\BeInfluencer::class)->name('be_influencer');
            Route::get('/job/opportunities', \App\Http\Livewire\JobOpportunities::class)->name('job_opportunities');
        });

        Route::prefix('/be-influencer')->name('be_influencer_')->group(function () {
            Route::get('/tree/evolution', EvolutionArbre::class)->name('tree_evolution');
            Route::get('/tree/maintenance', EntretienArbre::class)->name('tree_maintenance');
            Route::get('/successful-sharing-pool', \App\Http\Livewire\SuccessfulSharingPool::class)->name('successful_sharing_pool');
        });

        Route::prefix('/savings')->name('savings_')->group(function () {
            Route::get('/recuperation/history', HistoriqueRecuperation::class)->name('recuperation_history');
            Route::get('/user/purchase', UserPurchaseHistory::class)->name('user_purchase');
        });

        Route::prefix('/biography')->name('biography_')->group(function () {
            Route::get('/academic-background', \App\Http\Livewire\AcademicBackground::class)->name('academic_background');
            Route::get('/career-experience', \App\Http\Livewire\CareerExperience::class)->name('career_experience');
            Route::get('/hard-skills', \App\Http\Livewire\HardSkills::class)->name('hard_skills');
            Route::get('/soft-skills', \App\Http\Livewire\SoftSkills::class)->name('soft_skills');
            Route::get('/personal-characterization', \App\Http\Livewire\PersonalCharacterization::class)->name('personal_characterization');
            Route::get('/NCDPersonality', \App\Http\Livewire\CDPersonality::class)->name('NCDPersonality');
            Route::get('/sensory-representation-system', \App\Http\Livewire\SensoryRepresentationSystem::class)->name('sensory_representation_system');
            Route::get('/MBTI', \App\Http\Livewire\MBTI::class)->name('MBTI');
            Route::get('/e-business-card', \App\Http\Livewire\EBusinessCard::class)->name('e_business_card');
            Route::get('/generating/pdf/report', \App\Http\Livewire\GeneratingPDFReport::class)->name('generating_pdf_report');
        });


        Route::get('/treeview', \App\Http\Livewire\treeview::class)->name('treeview');

        Route::get('/treeview', \App\Http\Livewire\treeview::class)->name('treeview');

        Route::get('/user/balance-sms', UserBalanceSMS::class)->name('user_balance_sms');
        Route::get('/user/balance-cb', UserBalanceCB::class)->name('user_balance_cb');
        Route::get('/user/balance-db', UserBalanceDB::class)->name('user_balance_db');
        Route::get('/user/balance-bfs', UserBalanceBFS::class)->name('user_balance_bfs');
        Route::get('/financial/transaction', FinancialTransaction::class)->name('financial_transaction');
        Route::get('/contact-number', ContactNumber::class)->name('contact_number');
        Route::get('/user/edit-contact', EditUserContact::class)->name('user_contact_edit');
        Route::get('/balances/exchange/funding/RequestPulicUser', RequestPublicUser::class)->name('user_request_public');
        Route::get('/balances/exchange/funding/strip', stripView::class)->name('payment_strip');
        Route::get('/paytabs', '\App\Http\Livewire\pay@test')->name('paytabs');
        Route::get('/hobbies', Hobbies::class)->name('hobbies');

        Route::get('/description', Description::class)->name('description');
        Route::get('/accept/request', AcceptFinancialRequest::class)->name('accept_financial_request')->middleware('CloseAuth');

        Route::prefix('/surveys')->name('surveys_')->group(function () {
            Route::middleware(['IsSuperAdmin'])->group(function () {
                Route::get('/index', \App\Http\Livewire\SurveyIndex::class)->name('index');
            });
            Route::get('/archive', \App\Http\Livewire\SurveyArchive::class)->name('archive');
            Route::get('/', \App\Http\Livewire\SurveyCreateUpdate::class)->name('create_update');
            Route::get('/show/{idSurvey}', \App\Http\Livewire\SurveyShow::class)->name('show');
            Route::get('/participate/{idSurvey}', \App\Http\Livewire\SurveyParicipate::class)->name('participate');
            Route::get('/results/{idSurvey}', \App\Http\Livewire\SurveyResult::class)->name('results');
            Route::get('/{idSurvey}/question', \App\Http\Livewire\SurveyQuestionCreateUpdate::class)->name('question_create_update');
            Route::get('/{idSurvey}/question/{idQuestion}/Choice', \App\Http\Livewire\SurveyQuestionChoiceCreateUpdate::class)->name('question_choice_create_update');
        });

        Route::middleware(['IsSuperAdmin'])->group(function () {
            Route::get('/user_list', \App\Http\Livewire\UsersList::class)->name('user_list');
            Route::get('/user/{idUser}/details', \App\Http\Livewire\UserDetails::class)->name('user_details');
            Route::get('/configuration/ha', ConfigurationHA::class)->name('configuration_ha');
            Route::get('/configuration/setting', \App\Http\Livewire\ConfigurationSetting::class)->name('configuration_setting');
            Route::get('/configuration/bo', \App\Http\Livewire\ConfigurationBO::class)->name('configuration_bo');
            Route::get('/configuration/amounts', \App\Http\Livewire\ConfigurationAmounts::class)->name('configuration_amounts');
            Route::get('/admin/edit', \App\Http\Livewire\EditAdmin::class)->name('edit_admin');
            Route::get('/countries_management', \App\Http\Livewire\CountriesManagement::class)->name('countries_management');
            Route::get('/requests/identification', identificationRequest::class)->name('requests_identification');
            Route::get('/requests/commited-investors', \App\Http\Livewire\CommitedRequest::class)->name('requests_commited_investors');
            Route::get('/requests/commited-investors/{id}/show', \App\Http\Livewire\CommitedRequestShow::class)->name('requests_commited_investors_show');
            Route::get('/requests/instructor', \App\Http\Livewire\InstructorRequest::class)->name('requests_instructor');
            Route::get('/requests/instructor/{id}/show', \App\Http\Livewire\InstructorRequestShow::class)->name('requests_instructor_show');
            Route::get('/translate', TranslateView::class)->name('translate');
            Route::get('/translate/model/data', \App\Http\Livewire\TranslateModelData::class)->name('translate_model_data');

            Route::prefix('/target')->name('target_')->group(function () {
                Route::get('/index', \App\Http\Livewire\TargetIndex::class)->name('index');
                Route::get('/', \App\Http\Livewire\TargetCreateUpdate::class)->name('create_update');
                Route::get('/show/{idTarget}', \App\Http\Livewire\TargetShow::class)->name('show');
                Route::get('/{idTarget}/group', \App\Http\Livewire\GroupCreateUpdate::class)->name('group_create_update');
                Route::get('/{idTarget}/condition', \App\Http\Livewire\ConditionCreateUpdate::class)->name('condition_create_update');
            });

            Route::prefix('/platform')->name('platform_')->group(function () {
                Route::get('/index', \App\Http\Livewire\Platform::class)->name('index');
                Route::get('/', \App\Http\Livewire\PlatformCreateUpdate::class)->name('create_update');
                Route::get('/{id}', \App\Http\Livewire\PlatformShow::class)->name('show');
            });

        });

        Route::get('/shares/solde', \App\Http\Livewire\SharesSolde::class)->name('shares_solde');
        Route::get('/sharessolde', \App\Http\Livewire\SharesSolde::class)->name('sharessolde');

        Route::middleware(['IsSuperAdmin'])->group(function () {
            Route::get('/stat/countrie', \App\Http\Livewire\StatCountrie::class)->name('stat_countrie');
            Route::prefix('/shares-sold')->name('shares_sold_')->group(function () {
                Route::get('dashboard', \App\Http\Livewire\SharesSold::class)->name('dashboard');
                Route::get('/market-status', \App\Http\Livewire\SharesSoldMarketStatus::class)->name('market_status');
                Route::get('/recent-transaction', \App\Http\Livewire\SharesSoldRecentTransaction::class)->name('recent_transaction');
            });
        });

        Route::get('/stat-countries', 'App\Http\Controllers\ApiController@getCountriStat')->name('api_stat_countries');
        Route::post('/validate-phone', 'App\Http\Controllers\ApiController@validatePhone')->name('validate_phone');
        Route::post('/buy-action', 'App\Http\Controllers\ApiController@buyAction')->name('buyAction');
        Route::get('/action-by-ammount', 'App\Http\Controllers\ApiController@actionByAmmount')->name('action_by_ammount');
        Route::post('/gift-action-by-ammount', 'App\Http\Controllers\ApiController@giftActionByAmmount')->name('gift_action_by_ammount');
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
        Route::get('/url/list/{idUser}/{idamount}', 'App\Http\Controllers\ApiController@getUrlList')->name('url_list');
        Route::get('/action/historys', 'App\Http\Controllers\ApiController@getActionHistorys')->name('api_action_history');
        Route::get('/user/contacts', 'App\Http\Controllers\ApiController@getUserContacts')->name('api_user_contacts');
        Route::get('/user-balances/{idAmounts}', 'App\Http\Controllers\ApiController@getUserBalances')->name('api_user_balances');
        Route::get('/user-balances-list/{idUser}/{idAmounts}', 'App\Http\Controllers\ApiController@getUserBalancesList')->name('api_user_balances_list');
        Route::get('/shares-solde-list/{idUser}', 'App\Http\Controllers\ApiController@getSharesSoldeList')->name('api_shares_solde_list');
        Route::get('/user/admin', 'App\Http\Controllers\ApiController@getUserAdmin')->name('api_user_admin');
        Route::get('/history/notification', 'App\Http\Controllers\ApiController@getHistoryNotification')->name('api_history_notification');
        Route::get('/platforms', 'App\Http\Controllers\ApiController@getPlatforms')->name('api_platform');
        Route::get('/request', 'App\Http\Controllers\ApiController@getRequest')->name('api_request');
        Route::get('/representatives', 'App\Http\Controllers\ApiController@getRepresentatives')->name('api_representatives');
        Route::get('/user/balancesCB', 'App\Http\Controllers\ApiController@getUserBalancesCB')->name('api_user_balances_cb');
        Route::get('/user/purchase', 'App\Http\Controllers\ApiController@getPurchaseUser')->name('api_user_purchase');
        Route::get('/user/manager', 'App\Http\Controllers\ApiController@getAllUsers')->name('api_user_manager');
        Route::get('/user/invitations', 'App\Http\Controllers\ApiController@getInvitationsUser')->name('api_user_invitations');
        Route::get('/user/purchaseBFS', 'App\Http\Controllers\ApiController@getPurchaseBFSUser')->name('api_user_bfs_purchase');
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


