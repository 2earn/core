<?php

/*
|--------------------------------------------------------------------------
| Load The Cached Routes
|--------------------------------------------------------------------------
|
| Here we will decode and unserialize the RouteCollection instance that
| holds all of the route information for an application. This allows
| us to instantaneously load the entire route map into the router.
|
*/

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/_debugbar/open' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'debugbar.openhandler',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/_debugbar/assets/stylesheets' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'debugbar.assets.css',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/_debugbar/assets/javascript' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'debugbar.assets.js',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/oauth/token' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'passport.token',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/oauth/authorize' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'passport.authorizations.authorize',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'passport.authorizations.approve',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'passport.authorizations.deny',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/oauth/token/refresh' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'passport.token.refresh',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/oauth/tokens' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'passport.tokens.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/oauth/clients' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'passport.clients.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'passport.clients.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/oauth/scopes' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'passport.scopes.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/oauth/personal-access-tokens' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'passport.personal.tokens.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'passport.personal.tokens.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/sanctum/csrf-cookie' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ZZsZHGZzZofQueZi',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/livewire/upload-file' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'livewire.upload-file',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/livewire/livewire.js' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xFTdjB1RlP38Qinr',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/livewire/livewire.js.map' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ehxigNWVKhOHyhl3',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/config/EarnLog' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'blv.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/config/EarnLog/is-scan-required' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'blv.is-scan-required',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/config/EarnLog/scan-files' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'blv.scan-files',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/config/EarnLog/search-progress' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'blv.search-more',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/manifest.json' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'laravelpwa.manifest',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/offline' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0XPHZm8typBtsnOV',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/_ignition/health-check' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ignition.healthCheck',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/_ignition/execute-solution' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ignition.executeSolution',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/_ignition/update-config' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ignition.updateConfig',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/paymentIPN' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'payment_ipn',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/user' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Nd8EBfqJKZpsxLVS',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/logmeout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::K1TuNdxhZUMu3gLE',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pdf' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::CDhmvc6DAjVHtjJp',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tables-datatables' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/coming-soon-Move' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coming_move',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/coming-soon-learn' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coming_learn',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/coming-soon-shop' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'coming_shop',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/widgets' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'widgets',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/countries' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_countries',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_settings',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/balance/operations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_bal_operations',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/amounts' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_Amounts',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/action/historys' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_action_history',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/user/contacts' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_user_contacts',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/user/admin' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_user_admin',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/history/notification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_history_notification',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/request' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_request',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/representatives' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_representatives',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/user/balancesCB' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_user_balances_cb',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/user/purchase' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_user_purchase',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/user/manager' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_user_manager',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/user/invitations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_user_invitations',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/user/purchaseBFS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_user_bfs_purchase',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/handle/paytabs/notification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'handle_paytabs_notification',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/sankey' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'API_sankey',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/api/shares/solde' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_shares_solde',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/api/shares/soldes' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_shares_soldes',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/api/transfert' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_transfert',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/api/user/cash' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_user_cash',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/api/share/evolution' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_share_evolution',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/api/share/evolution/date' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_share_evolution_date',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/api/share/evolution/week' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_share_evolution_week',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/api/share/evolution/month' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_share_evolution_month',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/api/share/evolution/day' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_share_evolution_day',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/api/share/evolution/user' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_share_evolution_user',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/api/action/values' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_action_values',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/get-updated-card-content' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'get-updated-card-content',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/add-cash' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'add_cash',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/vip' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'vip',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/send-sms' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'send_sms',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/update-balance-status' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'update-balance-status',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/update-reserve-date' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'update-reserve-date',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/API/update-balance-real' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'update-balance-real',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/reset-not' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'reset_incoming_notification',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/reset-not-oOut' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'reset_out_going_notification',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::dKg8oq11fvmjgDcq',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/store-form' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'save_ph',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mail-verif' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'mailVerif',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mail-verif-Opt' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'mail_verif_opt',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/mail-verif-New' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'mail_verif_New',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/send-mail-notification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'sendMail',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/members' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'members',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/get-request-ajax' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'get_request_ajax',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/logout-sso' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'logout_sso',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tests' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::nxu5gjfdaGDBXA7u',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/_debugbar/c(?|lockwork/([^/]++)(*:39)|ache/([^/]++)(?:/([^/]++))?(*:73))|/oauth/(?|tokens/([^/]++)(*:106)|clients/([^/]++)(?|(*:133))|personal\\-access\\-tokens/([^/]++)(*:175))|/livewire/message/([^/]++)(*:210)|/([^/]++)/livewire/message/([^/]++)(*:253)|/livewire/preview\\-file/([^/]++)(*:293)|/config/EarnLog/f(?|ile/([^/]++)/download(*:342)|older/([^/]++)/download(*:373))|/([a-zA-Z]{2})(?|/(?|p(?|rivacy(*:413)|aytabs(*:427))|ho(?|me(*:443)|bbies(*:456))|a(?|c(?|c(?|ount(*:481)|ept/request(*:500))|tion\\-by\\-ammount(*:526))|dmin/identification_request(*:562))|c(?|o(?|n(?|tact(?|s(*:591)|\\-number(*:607))|figuration\\-(?|ha(*:633)|setting(*:648)|bo(*:658)|amounts(*:673)))|untries_management(*:701))|h(?|angePassword/([^/]++)(*:735)|eck\\-opt\\-code/([^/]++)/([^/]++)/([^/]++)(*:784)))|notification/(?|history(*:817)|settings(*:833))|user(?|/(?|purchase(*:861)|balance\\-(?|sms(*:884)|cb(*:894)|db(*:904)|bfs(*:915))|editContact(*:935))|_list(*:949)|s/list(*:963))|s(?|tat(?|/countrie(*:991)|\\-countries(*:1010))|hares(?|/solde(*:1034)|solde(*:1048)|_sold(?|(*:1065)|_(?|market_status(*:1091)|recent_transaction(*:1118)))))|tr(?|ee(?|view(*:1145)|/(?|evolution(*:1167)|maintenance(*:1187)))|anslation(*:1207))|f(?|inancial/transaction(*:1241)|orget\\-password(*:1265))|b(?|alances/exchange/funding/(?|RequestPulicUser(*:1323)|strip(*:1337))|uy\\-action(*:1357))|re(?|cuperation/history(*:1390)|gistre(*:1405))|description(*:1426)|edit_admin(*:1445)|validate\\-(?|phone(*:1472)|account(*:1488))|gift\\-action\\-by\\-ammount(*:1523)|login(*:1537))|(*:1547))|/API/(?|u(?|rl/list/([^/]++)/([^/]++)(*:1594)|ser\\-balances(?|/([^/]++)(*:1628)|\\-list/([^/]++)/([^/]++)(*:1661)))|shares\\-solde\\-list/([^/]++)(*:1700))|/([^/]++)(*:1719))/?$}sDu',
    ),
    3 => 
    array (
      39 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'debugbar.clockwork',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      73 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'debugbar.cache.delete',
            'tags' => NULL,
          ),
          1 => 
          array (
            0 => 'key',
            1 => 'tags',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      106 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'passport.tokens.destroy',
          ),
          1 => 
          array (
            0 => 'token_id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      133 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'passport.clients.update',
          ),
          1 => 
          array (
            0 => 'client_id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'passport.clients.destroy',
          ),
          1 => 
          array (
            0 => 'client_id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      175 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'passport.personal.tokens.destroy',
          ),
          1 => 
          array (
            0 => 'token_id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      210 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'livewire.message',
          ),
          1 => 
          array (
            0 => 'name',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      253 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'livewire.message-localized',
          ),
          1 => 
          array (
            0 => 'locale',
            1 => 'name',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      293 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'livewire.preview-file',
          ),
          1 => 
          array (
            0 => 'filename',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      342 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'blv.download-file',
          ),
          1 => 
          array (
            0 => 'fileIdentifier',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      373 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'blv.download-folder',
          ),
          1 => 
          array (
            0 => 'folderIdentifier',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      413 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'privacy',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      427 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'paytabs',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      443 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'home',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      456 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'hobbies',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      481 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'account',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      500 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accept_financial_request',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      526 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'action_by_ammount',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      562 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'identification_request',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      591 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'contacts',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      607 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'contact_number',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      633 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'configuration-ha',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      648 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'configuration-setting',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      658 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'configuration-bo',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      673 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'configuration-amounts',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      701 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'countries_management',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      735 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'reset_password',
          ),
          1 => 
          array (
            0 => 'locale',
            1 => 'idUser',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      784 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'check_opt_code',
          ),
          1 => 
          array (
            0 => 'locale',
            1 => 'iduser',
            2 => 'ccode',
            3 => 'numTel',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      817 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'notification_history',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      833 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'notification_settings',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      861 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user_purchase',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      884 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user_balance_sms',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      894 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user_balance_cb',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      904 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user_balance_db',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      915 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user_balance_bfs',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      935 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user_contact_edit',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      949 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user_list',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      963 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_users_list',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      991 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'stat_countrie',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1010 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_stat_countries',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1034 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'shares_solde',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1048 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'sharessolde',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1065 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'shares_sold',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1091 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'shares_sold_market_status',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1118 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'shares_sold_recent_transaction',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1145 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'treeview',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1167 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tree_evolution',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1187 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tree_maintenance',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1207 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'translate',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1241 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'financial_transaction',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1265 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'forget_password',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1323 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user_request_public',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1337 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'payment_strip',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1357 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'buyAction',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1390 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'recuperation_history',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1405 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'registre',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1426 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'description',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1445 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'edit_admin',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1472 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'validate_phone',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1488 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'validate_account',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1523 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'gift_action_by_ammount',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1537 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'login',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1547 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'main',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1594 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'url_list',
          ),
          1 => 
          array (
            0 => 'idUser',
            1 => 'idamount',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1628 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_user_balances',
          ),
          1 => 
          array (
            0 => 'idAmounts',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1661 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_user_balances_list',
          ),
          1 => 
          array (
            0 => 'idUser',
            1 => 'idAmounts',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1700 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'api_shares_solde_list',
          ),
          1 => 
          array (
            0 => 'idUser',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1719 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0fsLpM6kbWMktoym',
          ),
          1 => 
          array (
            0 => 'slug',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'debugbar.openhandler' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '_debugbar/open',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'Barryvdh\\Debugbar\\Middleware\\DebugbarEnabled',
        ),
        'uses' => 'Barryvdh\\Debugbar\\Controllers\\OpenHandlerController@handle',
        'as' => 'debugbar.openhandler',
        'controller' => 'Barryvdh\\Debugbar\\Controllers\\OpenHandlerController@handle',
        'namespace' => 'Barryvdh\\Debugbar\\Controllers',
        'prefix' => '_debugbar',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'debugbar.clockwork' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '_debugbar/clockwork/{id}',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'Barryvdh\\Debugbar\\Middleware\\DebugbarEnabled',
        ),
        'uses' => 'Barryvdh\\Debugbar\\Controllers\\OpenHandlerController@clockwork',
        'as' => 'debugbar.clockwork',
        'controller' => 'Barryvdh\\Debugbar\\Controllers\\OpenHandlerController@clockwork',
        'namespace' => 'Barryvdh\\Debugbar\\Controllers',
        'prefix' => '_debugbar',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'debugbar.assets.css' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '_debugbar/assets/stylesheets',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'Barryvdh\\Debugbar\\Middleware\\DebugbarEnabled',
        ),
        'uses' => 'Barryvdh\\Debugbar\\Controllers\\AssetController@css',
        'as' => 'debugbar.assets.css',
        'controller' => 'Barryvdh\\Debugbar\\Controllers\\AssetController@css',
        'namespace' => 'Barryvdh\\Debugbar\\Controllers',
        'prefix' => '_debugbar',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'debugbar.assets.js' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '_debugbar/assets/javascript',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'Barryvdh\\Debugbar\\Middleware\\DebugbarEnabled',
        ),
        'uses' => 'Barryvdh\\Debugbar\\Controllers\\AssetController@js',
        'as' => 'debugbar.assets.js',
        'controller' => 'Barryvdh\\Debugbar\\Controllers\\AssetController@js',
        'namespace' => 'Barryvdh\\Debugbar\\Controllers',
        'prefix' => '_debugbar',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'debugbar.cache.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => '_debugbar/cache/{key}/{tags?}',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'Barryvdh\\Debugbar\\Middleware\\DebugbarEnabled',
        ),
        'uses' => 'Barryvdh\\Debugbar\\Controllers\\CacheController@delete',
        'as' => 'debugbar.cache.delete',
        'controller' => 'Barryvdh\\Debugbar\\Controllers\\CacheController@delete',
        'namespace' => 'Barryvdh\\Debugbar\\Controllers',
        'prefix' => '_debugbar',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.token' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'oauth/token',
      'action' => 
      array (
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\AccessTokenController@issueToken',
        'as' => 'passport.token',
        'middleware' => 'throttle',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\AccessTokenController@issueToken',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.authorizations.authorize' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'oauth/authorize',
      'action' => 
      array (
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\AuthorizationController@authorize',
        'as' => 'passport.authorizations.authorize',
        'middleware' => 'web',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\AuthorizationController@authorize',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.token.refresh' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'oauth/token/refresh',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\TransientTokenController@refresh',
        'as' => 'passport.token.refresh',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\TransientTokenController@refresh',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.authorizations.approve' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'oauth/authorize',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\ApproveAuthorizationController@approve',
        'as' => 'passport.authorizations.approve',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\ApproveAuthorizationController@approve',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.authorizations.deny' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'oauth/authorize',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\DenyAuthorizationController@deny',
        'as' => 'passport.authorizations.deny',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\DenyAuthorizationController@deny',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.tokens.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'oauth/tokens',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\AuthorizedAccessTokenController@forUser',
        'as' => 'passport.tokens.index',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\AuthorizedAccessTokenController@forUser',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.tokens.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'oauth/tokens/{token_id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\AuthorizedAccessTokenController@destroy',
        'as' => 'passport.tokens.destroy',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\AuthorizedAccessTokenController@destroy',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.clients.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'oauth/clients',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\ClientController@forUser',
        'as' => 'passport.clients.index',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\ClientController@forUser',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.clients.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'oauth/clients',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\ClientController@store',
        'as' => 'passport.clients.store',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\ClientController@store',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.clients.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'oauth/clients/{client_id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\ClientController@update',
        'as' => 'passport.clients.update',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\ClientController@update',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.clients.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'oauth/clients/{client_id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\ClientController@destroy',
        'as' => 'passport.clients.destroy',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\ClientController@destroy',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.scopes.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'oauth/scopes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\ScopeController@all',
        'as' => 'passport.scopes.index',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\ScopeController@all',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.personal.tokens.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'oauth/personal-access-tokens',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\PersonalAccessTokenController@forUser',
        'as' => 'passport.personal.tokens.index',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\PersonalAccessTokenController@forUser',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.personal.tokens.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'oauth/personal-access-tokens',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\PersonalAccessTokenController@store',
        'as' => 'passport.personal.tokens.store',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\PersonalAccessTokenController@store',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'passport.personal.tokens.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'oauth/personal-access-tokens/{token_id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Passport\\Http\\Controllers\\PersonalAccessTokenController@destroy',
        'as' => 'passport.personal.tokens.destroy',
        'controller' => 'Laravel\\Passport\\Http\\Controllers\\PersonalAccessTokenController@destroy',
        'namespace' => 'Laravel\\Passport\\Http\\Controllers',
        'prefix' => 'oauth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZZsZHGZzZofQueZi' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sanctum/csrf-cookie',
      'action' => 
      array (
        'uses' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'controller' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'namespace' => NULL,
        'prefix' => 'sanctum',
        'where' => 
        array (
        ),
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'generated::ZZsZHGZzZofQueZi',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'livewire.message' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'livewire/message/{name}',
      'action' => 
      array (
        'uses' => 'Livewire\\Controllers\\HttpConnectionHandler@__invoke',
        'controller' => 'Livewire\\Controllers\\HttpConnectionHandler',
        'as' => 'livewire.message',
        'middleware' => 
        array (
          0 => 'web',
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'livewire.message-localized' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '{locale}/livewire/message/{name}',
      'action' => 
      array (
        'uses' => 'Livewire\\Controllers\\HttpConnectionHandler@__invoke',
        'controller' => 'Livewire\\Controllers\\HttpConnectionHandler',
        'as' => 'livewire.message-localized',
        'middleware' => 
        array (
          0 => 'web',
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'livewire.upload-file' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'livewire/upload-file',
      'action' => 
      array (
        'uses' => 'Livewire\\Controllers\\FileUploadHandler@handle',
        'controller' => 'Livewire\\Controllers\\FileUploadHandler@handle',
        'as' => 'livewire.upload-file',
        'middleware' => 
        array (
          0 => 'web',
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'livewire.preview-file' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'livewire/preview-file/{filename}',
      'action' => 
      array (
        'uses' => 'Livewire\\Controllers\\FilePreviewHandler@handle',
        'controller' => 'Livewire\\Controllers\\FilePreviewHandler@handle',
        'as' => 'livewire.preview-file',
        'middleware' => 
        array (
          0 => 'web',
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xFTdjB1RlP38Qinr' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'livewire/livewire.js',
      'action' => 
      array (
        'uses' => 'Livewire\\Controllers\\LivewireJavaScriptAssets@source',
        'controller' => 'Livewire\\Controllers\\LivewireJavaScriptAssets@source',
        'as' => 'generated::xFTdjB1RlP38Qinr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ehxigNWVKhOHyhl3' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'livewire/livewire.js.map',
      'action' => 
      array (
        'uses' => 'Livewire\\Controllers\\LivewireJavaScriptAssets@maps',
        'controller' => 'Livewire\\Controllers\\LivewireJavaScriptAssets@maps',
        'as' => 'generated::ehxigNWVKhOHyhl3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'blv.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'config/EarnLog',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'Opcodes\\LogViewer\\Http\\Controllers\\IndexController@__invoke',
        'controller' => 'Opcodes\\LogViewer\\Http\\Controllers\\IndexController',
        'namespace' => NULL,
        'prefix' => '/config/EarnLog',
        'where' => 
        array (
        ),
        'as' => 'blv.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'blv.download-file' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'config/EarnLog/file/{fileIdentifier}/download',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'Opcodes\\LogViewer\\Http\\Controllers\\DownloadFileController@__invoke',
        'controller' => 'Opcodes\\LogViewer\\Http\\Controllers\\DownloadFileController',
        'namespace' => NULL,
        'prefix' => '/config/EarnLog',
        'where' => 
        array (
        ),
        'as' => 'blv.download-file',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'blv.download-folder' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'config/EarnLog/folder/{folderIdentifier}/download',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'Opcodes\\LogViewer\\Http\\Controllers\\DownloadFolderController@__invoke',
        'controller' => 'Opcodes\\LogViewer\\Http\\Controllers\\DownloadFolderController',
        'namespace' => NULL,
        'prefix' => '/config/EarnLog',
        'where' => 
        array (
        ),
        'as' => 'blv.download-folder',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'blv.is-scan-required' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'config/EarnLog/is-scan-required',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'Opcodes\\LogViewer\\Http\\Controllers\\IsScanRequiredController@__invoke',
        'controller' => 'Opcodes\\LogViewer\\Http\\Controllers\\IsScanRequiredController',
        'namespace' => NULL,
        'prefix' => '/config/EarnLog',
        'where' => 
        array (
        ),
        'as' => 'blv.is-scan-required',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'blv.scan-files' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'config/EarnLog/scan-files',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'Opcodes\\LogViewer\\Http\\Controllers\\ScanFilesController@__invoke',
        'controller' => 'Opcodes\\LogViewer\\Http\\Controllers\\ScanFilesController',
        'namespace' => NULL,
        'prefix' => '/config/EarnLog',
        'where' => 
        array (
        ),
        'as' => 'blv.scan-files',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'blv.search-more' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'config/EarnLog/search-progress',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'Opcodes\\LogViewer\\Http\\Controllers\\SearchProgressController@__invoke',
        'controller' => 'Opcodes\\LogViewer\\Http\\Controllers\\SearchProgressController',
        'namespace' => NULL,
        'prefix' => '/config/EarnLog',
        'where' => 
        array (
        ),
        'as' => 'blv.search-more',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'laravelpwa.manifest' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'manifest.json',
      'action' => 
      array (
        'middleware' => 'web',
        'uses' => 'LaravelPWA\\Http\\Controllers\\LaravelPWAController@manifestJson',
        'controller' => 'LaravelPWA\\Http\\Controllers\\LaravelPWAController@manifestJson',
        'as' => 'laravelpwa.manifest',
        'namespace' => 'LaravelPWA\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0XPHZm8typBtsnOV' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'offline',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:47:"Laravel\\SerializableClosure\\SerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Signed":2:{s:12:"serializable";s:273:"O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:55:"function () {
    return \\view(\'livewire.offline\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000084c0000000000000000";}";s:4:"hash";s:44:"rLam21i9JIlOeYOhAvG+oVgvjxxokxvoNLJUFzisS2g=";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::0XPHZm8typBtsnOV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'ignition.healthCheck' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '_ignition/health-check',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'Spatie\\LaravelIgnition\\Http\\Middleware\\RunnableSolutionsEnabled',
        ),
        'uses' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\HealthCheckController@__invoke',
        'controller' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\HealthCheckController',
        'as' => 'ignition.healthCheck',
        'namespace' => NULL,
        'prefix' => '_ignition',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'ignition.executeSolution' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '_ignition/execute-solution',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'Spatie\\LaravelIgnition\\Http\\Middleware\\RunnableSolutionsEnabled',
        ),
        'uses' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\ExecuteSolutionController@__invoke',
        'controller' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\ExecuteSolutionController',
        'as' => 'ignition.executeSolution',
        'namespace' => NULL,
        'prefix' => '_ignition',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'ignition.updateConfig' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '_ignition/update-config',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'Spatie\\LaravelIgnition\\Http\\Middleware\\RunnableSolutionsEnabled',
        ),
        'uses' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\UpdateConfigController@__invoke',
        'controller' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\UpdateConfigController',
        'as' => 'ignition.updateConfig',
        'namespace' => NULL,
        'prefix' => '_ignition',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'payment_ipn' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'paymentIPN',
      'action' => 
      array (
        'uses' => 'Paytabscom\\Laravel_paytabs\\Controllers\\PaytabsLaravelListenerApi@paymentIPN',
        'controller' => 'Paytabscom\\Laravel_paytabs\\Controllers\\PaytabsLaravelListenerApi@paymentIPN',
        'as' => 'payment_ipn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Nd8EBfqJKZpsxLVS' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
        ),
        'uses' => 'O:47:"Laravel\\SerializableClosure\\SerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Signed":2:{s:12:"serializable";s:297:"O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:79:"function (\\Illuminate\\Http\\Request $request) {
    return $request->user();
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000008410000000000000000";}";s:4:"hash";s:44:"CiJvtMhD6GFQ2MFZIx5wFRZW7g/igPzcmhkxhS/wzjA=";}}',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::Nd8EBfqJKZpsxLVS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::K1TuNdxhZUMu3gLE' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/logmeout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:api',
        ),
        'uses' => 'O:47:"Laravel\\SerializableClosure\\SerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Signed":2:{s:12:"serializable";s:635:"O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:416:"function (\\Illuminate\\Http\\Request $request) {
    $user =  $request->user();
    $accessToken = $user->token();
    \\Illuminate\\Support\\Facades\\DB::table(\'oauth_refresh_tokens\')
    ->where(\'access_token_id\', $accessToken->id)
    ->delete();
    $user->token()->delete();


    return \\response()->json([
        \'message\' => \'Successfully logged out\',
        \'session\' => \\session()->all()
    ]);
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000008430000000000000000";}";s:4:"hash";s:44:"lXOMNwGNYnlwhZi113ZPvmUwH1ofGy8IVEyjM1apBCI=";}}',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::K1TuNdxhZUMu3gLE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::CDhmvc6DAjVHtjJp' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pdf',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:47:"Laravel\\SerializableClosure\\SerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Signed":2:{s:12:"serializable";s:540:"O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:321:"function () {
    $pdf = \\Barryvdh\\DomPDF\\Facade\\Pdf::loadView(\'pdf\');
    \\Illuminate\\Support\\Facades\\Mail::send(\'pwd_email\', [\'data\' => "azerty"], function ($message) use ($pdf) {
        $message->to(\'khalil@2earn.cash\')->subject(\'Invoice\');
        $message->attachData($pdf->output(), "invoice.pdf");
    });
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000008450000000000000000";}";s:4:"hash";s:44:"SdY1PLn/8q+R0auNW4203ze2+5kLG35YzT+opgrZNU4=";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::CDhmvc6DAjVHtjJp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tables-datatables',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@index',
        'controller' => 'App\\Http\\Controllers\\HomeController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'coming_move' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'coming-soon-Move',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@index',
        'controller' => 'App\\Http\\Controllers\\HomeController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'coming_move',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'coming_learn' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'coming-soon-learn',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@index',
        'controller' => 'App\\Http\\Controllers\\HomeController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'coming_learn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'coming_shop' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'coming-soon-shop',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@index',
        'controller' => 'App\\Http\\Controllers\\HomeController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'coming_shop',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'widgets' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'widgets',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@index',
        'controller' => 'App\\Http\\Controllers\\HomeController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'widgets',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'privacy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/privacy',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
        ),
        'uses' => 'O:47:"Laravel\\SerializableClosure\\SerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Signed":2:{s:12:"serializable";s:341:"O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:122:"function () {
        return \\view(\'livewire.privacy\')->extends(\'layouts.master-without-nav\')->section(\'content\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000084f0000000000000000";}";s:4:"hash";s:44:"Vaj33ayvyGA8Yls58YYEimaCsqBIj23ZFVy+nv4JWv0=";}}',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'privacy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'main' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\Home@__invoke',
        'controller' => 'App\\Http\\Livewire\\Home',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'main',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'home' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/home',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\Home@__invoke',
        'controller' => 'App\\Http\\Livewire\\Home',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'home',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'account' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/account',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\Account@__invoke',
        'controller' => 'App\\Http\\Livewire\\Account',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'account',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'contacts' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/contacts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\Contacts@__invoke',
        'controller' => 'App\\Http\\Livewire\\Contacts',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'contacts',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'notification_history' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/notification/history',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\NotificationHistory@__invoke',
        'controller' => 'App\\Http\\Livewire\\NotificationHistory',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'notification_history',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'notification_settings' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/notification/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\NotificationSettings@__invoke',
        'controller' => 'App\\Http\\Livewire\\NotificationSettings',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'notification_settings',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user_purchase' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/user/purchase',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\UserPurchaseHistory@__invoke',
        'controller' => 'App\\Http\\Livewire\\UserPurchaseHistory',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'user_purchase',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'stat_countrie' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/stat/countrie',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\StatCountrie@__invoke',
        'controller' => 'App\\Http\\Livewire\\StatCountrie',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'stat_countrie',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'shares_solde' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/shares/solde',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\SharesSolde@__invoke',
        'controller' => 'App\\Http\\Livewire\\SharesSolde',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'shares_solde',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'treeview' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/treeview',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\treeview@__invoke',
        'controller' => 'App\\Http\\Livewire\\treeview',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'treeview',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'sharessolde' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/sharessolde',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\SharesSolde@__invoke',
        'controller' => 'App\\Http\\Livewire\\SharesSolde',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'sharessolde',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'shares_sold' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/shares_sold',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\SharesSold@__invoke',
        'controller' => 'App\\Http\\Livewire\\SharesSold',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'shares_sold',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'shares_sold_market_status' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/shares_sold_market_status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\SharesSoldMarketStatus@__invoke',
        'controller' => 'App\\Http\\Livewire\\SharesSoldMarketStatus',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'shares_sold_market_status',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'shares_sold_recent_transaction' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/shares_sold_recent_transaction',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\SharesSoldRecentTransaction@__invoke',
        'controller' => 'App\\Http\\Livewire\\SharesSoldRecentTransaction',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'shares_sold_recent_transaction',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user_balance_sms' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/user/balance-sms',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\UserBalanceSMS@__invoke',
        'controller' => 'App\\Http\\Livewire\\UserBalanceSMS',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'user_balance_sms',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user_balance_cb' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/user/balance-cb',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\UserBalanceCB@__invoke',
        'controller' => 'App\\Http\\Livewire\\UserBalanceCB',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'user_balance_cb',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user_balance_db' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/user/balance-db',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\UserBalanceDB@__invoke',
        'controller' => 'App\\Http\\Livewire\\UserBalanceDB',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'user_balance_db',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user_balance_bfs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/user/balance-bfs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\UserBalanceBFS@__invoke',
        'controller' => 'App\\Http\\Livewire\\UserBalanceBFS',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'user_balance_bfs',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'financial_transaction' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/financial/transaction',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\FinancialTransaction@__invoke',
        'controller' => 'App\\Http\\Livewire\\FinancialTransaction',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'financial_transaction',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'contact_number' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/contact-number',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\ContactNumber@__invoke',
        'controller' => 'App\\Http\\Livewire\\ContactNumber',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'contact_number',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user_contact_edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/user/editContact',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\EditUserContact@__invoke',
        'controller' => 'App\\Http\\Livewire\\EditUserContact',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'user_contact_edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user_request_public' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/balances/exchange/funding/RequestPulicUser',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\RequestPublicUser@__invoke',
        'controller' => 'App\\Http\\Livewire\\RequestPublicUser',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'user_request_public',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'payment_strip' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/balances/exchange/funding/strip',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\StripView@__invoke',
        'controller' => 'App\\Http\\Livewire\\StripView',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'payment_strip',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'paytabs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/paytabs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => '\\App\\Http\\Livewire\\pay@test',
        'controller' => '\\App\\Http\\Livewire\\pay@test',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'paytabs',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'hobbies' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/hobbies',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\Hobbies@__invoke',
        'controller' => 'App\\Http\\Livewire\\Hobbies',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'hobbies',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'recuperation_history' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/recuperation/history',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\HistoriqueRecuperation@__invoke',
        'controller' => 'App\\Http\\Livewire\\HistoriqueRecuperation',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'recuperation_history',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tree_evolution' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/tree/evolution',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\EvolutionArbre@__invoke',
        'controller' => 'App\\Http\\Livewire\\EvolutionArbre',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'tree_evolution',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tree_maintenance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/tree/maintenance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\EntretienArbre@__invoke',
        'controller' => 'App\\Http\\Livewire\\EntretienArbre',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'tree_maintenance',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'description' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/description',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Livewire\\Description@__invoke',
        'controller' => 'App\\Http\\Livewire\\Description',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'description',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accept_financial_request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/accept/request',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
          3 => 'CloseAuth',
        ),
        'uses' => 'App\\Http\\Livewire\\AcceptFinancialRequest@__invoke',
        'controller' => 'App\\Http\\Livewire\\AcceptFinancialRequest',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'accept_financial_request',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user_list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/user_list',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
          3 => 'IsSuperAdmin',
        ),
        'uses' => 'App\\Http\\Livewire\\UsersList@__invoke',
        'controller' => 'App\\Http\\Livewire\\UsersList',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'user_list',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'configuration-ha' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/configuration-ha',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
          3 => 'IsSuperAdmin',
        ),
        'uses' => 'App\\Http\\Livewire\\ConfigurationHA@__invoke',
        'controller' => 'App\\Http\\Livewire\\ConfigurationHA',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'configuration-ha',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'configuration-setting' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/configuration-setting',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
          3 => 'IsSuperAdmin',
        ),
        'uses' => 'App\\Http\\Livewire\\ConfigurationSetting@__invoke',
        'controller' => 'App\\Http\\Livewire\\ConfigurationSetting',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'configuration-setting',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'configuration-bo' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/configuration-bo',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
          3 => 'IsSuperAdmin',
        ),
        'uses' => 'App\\Http\\Livewire\\ConfigurationBO@__invoke',
        'controller' => 'App\\Http\\Livewire\\ConfigurationBO',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'configuration-bo',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'configuration-amounts' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/configuration-amounts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
          3 => 'IsSuperAdmin',
        ),
        'uses' => 'App\\Http\\Livewire\\ConfigurationAmounts@__invoke',
        'controller' => 'App\\Http\\Livewire\\ConfigurationAmounts',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'configuration-amounts',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'edit_admin' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/edit_admin',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
          3 => 'IsSuperAdmin',
        ),
        'uses' => 'App\\Http\\Livewire\\EditAdmin@__invoke',
        'controller' => 'App\\Http\\Livewire\\EditAdmin',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'edit_admin',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'countries_management' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/countries_management',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
          3 => 'IsSuperAdmin',
        ),
        'uses' => 'App\\Http\\Livewire\\CountriesManagement@__invoke',
        'controller' => 'App\\Http\\Livewire\\CountriesManagement',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'countries_management',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'identification_request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/admin/identification_request',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
          3 => 'IsSuperAdmin',
        ),
        'uses' => 'App\\Http\\Livewire\\IdentificationRequest@__invoke',
        'controller' => 'App\\Http\\Livewire\\IdentificationRequest',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'identification_request',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'translate' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/translation',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
          3 => 'IsSuperAdmin',
        ),
        'uses' => 'App\\Http\\Livewire\\TranslateView@__invoke',
        'controller' => 'App\\Http\\Livewire\\TranslateView',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'translate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_stat_countries' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/stat-countries',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getCountriStat',
        'controller' => 'App\\Http\\Controllers\\ApiController@getCountriStat',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'api_stat_countries',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'validate_phone' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '{locale}/validate-phone',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@validatePhone',
        'controller' => 'App\\Http\\Controllers\\ApiController@validatePhone',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'validate_phone',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'buyAction' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '{locale}/buy-action',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@buyAction',
        'controller' => 'App\\Http\\Controllers\\ApiController@buyAction',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'buyAction',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'action_by_ammount' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/action-by-ammount',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@actionByAmmount',
        'controller' => 'App\\Http\\Controllers\\ApiController@actionByAmmount',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'action_by_ammount',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'gift_action_by_ammount' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '{locale}/gift-action-by-ammount',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@giftActionByAmmount',
        'controller' => 'App\\Http\\Controllers\\ApiController@giftActionByAmmount',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'gift_action_by_ammount',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'reset_password' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/changePassword/{idUser}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
        ),
        'uses' => 'App\\Http\\Livewire\\ChangePassword@__invoke',
        'controller' => 'App\\Http\\Livewire\\ChangePassword',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'reset_password',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_users_list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/users/list',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getUsersList',
        'controller' => 'App\\Http\\Controllers\\ApiController@getUsersList',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'api_users_list',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
          2 => 'setLocalLogin',
        ),
        'uses' => 'App\\Http\\Livewire\\Login@__invoke',
        'controller' => 'App\\Http\\Livewire\\Login',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'login',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'registre' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/registre',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
        ),
        'uses' => 'App\\Http\\Livewire\\Registre@__invoke',
        'controller' => 'App\\Http\\Livewire\\Registre',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'registre',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'forget_password' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/forget-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
        ),
        'uses' => 'App\\Http\\Livewire\\ForgotPassword@__invoke',
        'controller' => 'App\\Http\\Livewire\\ForgotPassword',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'forget_password',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'check_opt_code' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/check-opt-code/{iduser}/{ccode}/{numTel}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
        ),
        'uses' => 'App\\Http\\Livewire\\CheckOptCode@__invoke',
        'controller' => 'App\\Http\\Livewire\\CheckOptCode',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'check_opt_code',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'validate_account' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/validate-account',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'setlocale',
        ),
        'uses' => 'App\\Http\\Livewire\\ValidateAccount@__invoke',
        'controller' => 'App\\Http\\Livewire\\ValidateAccount',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => '[a-zA-Z]{2}',
        ),
        'as' => 'validate_account',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => '[a-zA-Z]{2}',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_countries' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/countries',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getCountries',
        'controller' => 'App\\Http\\Controllers\\ApiController@getCountries',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_countries',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_settings' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getSettings',
        'controller' => 'App\\Http\\Controllers\\ApiController@getSettings',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_settings',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_bal_operations' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/balance/operations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getBalanceOperations',
        'controller' => 'App\\Http\\Controllers\\ApiController@getBalanceOperations',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_bal_operations',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_Amounts' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/amounts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getAmounts',
        'controller' => 'App\\Http\\Controllers\\ApiController@getAmounts',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_Amounts',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'url_list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/url/list/{idUser}/{idamount}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getUrlList',
        'controller' => 'App\\Http\\Controllers\\ApiController@getUrlList',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'url_list',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_action_history' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/action/historys',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getActionHistorys',
        'controller' => 'App\\Http\\Controllers\\ApiController@getActionHistorys',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_action_history',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_user_contacts' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/user/contacts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getUserContacts',
        'controller' => 'App\\Http\\Controllers\\ApiController@getUserContacts',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_user_contacts',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_user_balances' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/user-balances/{idAmounts}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getUserBalances',
        'controller' => 'App\\Http\\Controllers\\ApiController@getUserBalances',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_user_balances',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_user_balances_list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/user-balances-list/{idUser}/{idAmounts}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getUserBalancesList',
        'controller' => 'App\\Http\\Controllers\\ApiController@getUserBalancesList',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_user_balances_list',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_shares_solde_list' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/shares-solde-list/{idUser}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getSharesSoldeList',
        'controller' => 'App\\Http\\Controllers\\ApiController@getSharesSoldeList',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_shares_solde_list',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_user_admin' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/user/admin',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getUserAdmin',
        'controller' => 'App\\Http\\Controllers\\ApiController@getUserAdmin',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_user_admin',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_history_notification' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/history/notification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getHistoryNotification',
        'controller' => 'App\\Http\\Controllers\\ApiController@getHistoryNotification',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_history_notification',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/request',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getRequest',
        'controller' => 'App\\Http\\Controllers\\ApiController@getRequest',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_request',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_representatives' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/representatives',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getRepresentatives',
        'controller' => 'App\\Http\\Controllers\\ApiController@getRepresentatives',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_representatives',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_user_balances_cb' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/user/balancesCB',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getUserBalancesCB',
        'controller' => 'App\\Http\\Controllers\\ApiController@getUserBalancesCB',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_user_balances_cb',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_user_purchase' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/user/purchase',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getPurchaseUser',
        'controller' => 'App\\Http\\Controllers\\ApiController@getPurchaseUser',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_user_purchase',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_user_manager' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/user/manager',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getAllUsers',
        'controller' => 'App\\Http\\Controllers\\ApiController@getAllUsers',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_user_manager',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_user_invitations' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/user/invitations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getInvitationsUser',
        'controller' => 'App\\Http\\Controllers\\ApiController@getInvitationsUser',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_user_invitations',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_user_bfs_purchase' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/user/purchaseBFS',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getPurchaseBFSUser',
        'controller' => 'App\\Http\\Controllers\\ApiController@getPurchaseBFSUser',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_user_bfs_purchase',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'handle_paytabs_notification' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'API/handle/paytabs/notification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@handlePaymentNotification',
        'controller' => 'App\\Http\\Controllers\\ApiController@handlePaymentNotification',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'handle_paytabs_notification',
        'excluded_middleware' => 
        array (
          0 => 'web',
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'API_sankey' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/sankey',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getSankey',
        'controller' => 'App\\Http\\Controllers\\ApiController@getSankey',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'API_sankey',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_shares_solde' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/api/shares/solde',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getSharesSolde',
        'controller' => 'App\\Http\\Controllers\\ApiController@getSharesSolde',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_shares_solde',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_shares_soldes' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/api/shares/soldes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getSharesSoldes',
        'controller' => 'App\\Http\\Controllers\\ApiController@getSharesSoldes',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_shares_soldes',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_transfert' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/api/transfert',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getTransfert',
        'controller' => 'App\\Http\\Controllers\\ApiController@getTransfert',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_transfert',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_user_cash' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/api/user/cash',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getUserCashBalance',
        'controller' => 'App\\Http\\Controllers\\ApiController@getUserCashBalance',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_user_cash',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_share_evolution' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/api/share/evolution',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getSharePriceEvolution',
        'controller' => 'App\\Http\\Controllers\\ApiController@getSharePriceEvolution',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_share_evolution',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_share_evolution_date' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/api/share/evolution/date',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getSharePriceEvolutionDate',
        'controller' => 'App\\Http\\Controllers\\ApiController@getSharePriceEvolutionDate',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_share_evolution_date',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_share_evolution_week' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/api/share/evolution/week',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getSharePriceEvolutionWeek',
        'controller' => 'App\\Http\\Controllers\\ApiController@getSharePriceEvolutionWeek',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_share_evolution_week',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_share_evolution_month' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/api/share/evolution/month',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getSharePriceEvolutionMonth',
        'controller' => 'App\\Http\\Controllers\\ApiController@getSharePriceEvolutionMonth',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_share_evolution_month',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_share_evolution_day' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/api/share/evolution/day',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getSharePriceEvolutionDay',
        'controller' => 'App\\Http\\Controllers\\ApiController@getSharePriceEvolutionDay',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_share_evolution_day',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_share_evolution_user' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/api/share/evolution/user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getSharePriceEvolutionUser',
        'controller' => 'App\\Http\\Controllers\\ApiController@getSharePriceEvolutionUser',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_share_evolution_user',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'api_action_values' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/api/action/values',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getActionValues',
        'controller' => 'App\\Http\\Controllers\\ApiController@getActionValues',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'api_action_values',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'get-updated-card-content' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'API/get-updated-card-content',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getUpdatedCardContent',
        'controller' => 'App\\Http\\Controllers\\ApiController@getUpdatedCardContent',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'get-updated-card-content',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'add_cash' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'API/add-cash',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@addCash',
        'controller' => 'App\\Http\\Controllers\\ApiController@addCash',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'add_cash',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'vip' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'API/vip',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@vip',
        'controller' => 'App\\Http\\Controllers\\ApiController@vip',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'vip',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'send_sms' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'API/send-sms',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@sendSMS',
        'controller' => 'App\\Http\\Controllers\\ApiController@sendSMS',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'send_sms',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'update-balance-status' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'API/update-balance-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@updateBalanceStatus',
        'controller' => 'App\\Http\\Controllers\\ApiController@updateBalanceStatus',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'update-balance-status',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'update-reserve-date' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'API/update-reserve-date',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@updateReserveDate',
        'controller' => 'App\\Http\\Controllers\\ApiController@updateReserveDate',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'update-reserve-date',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'update-balance-real' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'API/update-balance-real',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@updateBalanceReal',
        'controller' => 'App\\Http\\Controllers\\ApiController@updateBalanceReal',
        'namespace' => NULL,
        'prefix' => '/API',
        'where' => 
        array (
        ),
        'as' => 'update-balance-real',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'reset_incoming_notification' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'reset-not',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\FinancialRequestController@resetInComingNotification',
        'controller' => 'App\\Http\\Controllers\\FinancialRequestController@resetInComingNotification',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'reset_incoming_notification',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'reset_out_going_notification' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'reset-not-oOut',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\FinancialRequestController@resetOutGoingNotification',
        'controller' => 'App\\Http\\Controllers\\FinancialRequestController@resetOutGoingNotification',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'reset_out_going_notification',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::dKg8oq11fvmjgDcq' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:47:"Laravel\\SerializableClosure\\SerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Signed":2:{s:12:"serializable";s:278:"O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:60:"function () {
    return \\redirect(\\app()->getLocale());
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000008b70000000000000000";}";s:4:"hash";s:44:"SWBmKNHb8pPKvbA43qwkO5NjhZ/U7xDBaLQoIc4UOOI=";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::dKg8oq11fvmjgDcq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'save_ph' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'store-form',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PostController@store',
        'controller' => 'App\\Http\\Controllers\\PostController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'save_ph',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'mailVerif' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mail-verif',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PostController@verifyMail',
        'controller' => 'App\\Http\\Controllers\\PostController@verifyMail',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'mailVerif',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'mail_verif_opt' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mail-verif-Opt',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PostController@mailVerifOpt',
        'controller' => 'App\\Http\\Controllers\\PostController@mailVerifOpt',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'mail_verif_opt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'mail_verif_New' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'mail-verif-New',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PostController@mailVerifNew',
        'controller' => 'App\\Http\\Controllers\\PostController@mailVerifNew',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'mail_verif_New',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'sendMail' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'send-mail-notification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PostController@sendMail',
        'controller' => 'App\\Http\\Controllers\\PostController@sendMail',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'sendMail',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'members' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'members',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\PostController@getMember',
        'controller' => 'App\\Http\\Controllers\\PostController@getMember',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'members',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'get_request_ajax' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'get-request-ajax',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@getRequestAjax',
        'controller' => 'App\\Http\\Controllers\\ApiController@getRequestAjax',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'get_request_ajax',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'logout_sso' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'logout-sso',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\ApiController@logoutSSo',
        'controller' => 'App\\Http\\Controllers\\ApiController@logoutSSo',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'logout_sso',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::nxu5gjfdaGDBXA7u' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tests',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => '\\Illuminate\\Routing\\ViewController@__invoke',
        'controller' => '\\Illuminate\\Routing\\ViewController',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::nxu5gjfdaGDBXA7u',
      ),
      'fallback' => false,
      'defaults' => 
      array (
        'view' => 'tests',
        'data' => 
        array (
        ),
        'status' => 200,
        'headers' => 
        array (
        ),
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0fsLpM6kbWMktoym' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{slug}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:47:"Laravel\\SerializableClosure\\SerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Signed":2:{s:12:"serializable";s:278:"O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:60:"function () {
    return \\redirect(\\app()->getLocale());
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000008ad0000000000000000";}";s:4:"hash";s:44:"WwLmi2RgkbmWWhtXxXW4RqIegit2U5CI//busi1dueU=";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::0fsLpM6kbWMktoym',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
