<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?php echo e(URL::asset('assets/images/logo-sm.png')); ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e(URL::asset('assets/images/logo-dark.png')); ?>" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a id="MyHover" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?php echo e(URL::asset('assets/images/logo-sm.png')); ?>" alt="" height="40">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e(URL::asset('assets/images/logo-light.png')); ?>" alt="" height="30">
            </span>
        </a>

        
        <button onclick="testClick()" style="cursor: text" type="button"
                class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                

                <li class="nav-item">
                    <a href="<?php echo e(route('home',app()->getLocale())); ?>" class="nav-link menu-link" data-bs-toggle="" role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-home-gear-fill"></i> <span><?php echo e(__('Home')); ?></span>
                    </a>
                    
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('account',app()->getLocale() )); ?>" class="nav-link menu-link" data-bs-toggle="" role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-account-pin-circle-fill"></i> <span><?php echo e(__('Account')); ?></span>
                    </a>
                    
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('contacts',app()->getLocale())); ?>" class="nav-link menu-link" data-bs-toggle="" role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-contacts-fill"></i> <span><?php echo e(__('Contact')); ?></span>
                    </a>
                    
                </li>
                <li class="nav-item">



                    <a href="<?php echo e(route('user_purchase',app()->getLocale())); ?>" class="nav-link menu-link" data-bs-toggle="" role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">

                        <i class="ri-dashboard-2-line"></i> <span><?php echo e(__('Purchase history')); ?></span>
                    </a>
                    
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('notification_settings',app()->getLocale())); ?>" class="nav-link menu-link" data-bs-toggle=""
                       role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-notification-2-fill"></i> <span><?php echo e(__('Notification Settings')); ?></span>
                    </a>
                    
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('notification_history',app()->getLocale())); ?>" class="nav-link menu-link" data-bs-toggle=""
                       role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-dashboard-2-line"></i> <span><?php echo e(__('Notification history')); ?></span>
                    </a>
                    
                </li>
                <li class="nav-item">

                    <a href="<?php echo e(route('financial_transaction',app()->getLocale())); ?>" class="nav-link menu-link" data-bs-toggle="" role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">

                        <i class="ri-bank-fill"></i> <span><?php echo e(__('Exchange')); ?></span>
                    </a>
                    
                </li>
                <?php if(auth()->user()->getRoleNames()->first() =="Super admin"): ?>
                    <hr>
                    <li class="nav-item">

                        <a href="<?php echo e(route('configuration', app()->getLocale())); ?>" class="nav-link menu-link" data-bs-toggle="" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-settings-5-line"></i> <span><?php echo e(__('Settings')); ?></span>
                        </a>
                        
                    </li>
                    <li class="nav-item">

                        <a href=" " class="nav-link menu-link" data-bs-toggle="" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-user-settings-line"></i> <span><?php echo e(__('Administrators Management')); ?></span>
                        </a>
                        
                    </li>
                    <li class="nav-item">

                        <a href=" " class="nav-link menu-link" data-bs-toggle="" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-settings-line"></i> <span><?php echo e(__('representatives Management')); ?></span>
                        </a>
                        
                    </li>
                    <li class="nav-item">

                        <a href="<?php echo e(route('identificationRequest', app()->getLocale())); ?>" class="nav-link menu-link" data-bs-toggle="" role="button"

                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-git-pull-request-line"></i> <span><?php echo e(__('Identification Requests')); ?></span>
                        </a>
                        
                    </li>
                    <li class="nav-item">

                        <a href=" " class="nav-link menu-link" data-bs-toggle="" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-flag-line"></i> <span><?php echo e(__('Countries Management')); ?></span>
                        </a>
                        
                    </li>
                <?php endif; ?>
                <?php if(getExtraAdmin()=="0021653342666" || getExtraAdmin()=="0021629294046"): ?>
                    <li class="nav-item">

                        <a data-turbolinks="false" href="<?php echo e(route('translate', app()->getLocale())); ?>" class="nav-link menu-link" data-bs-toggle="" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-flag-line"></i> <span><?php echo e(__('Translate')); ?></span>
                        </a>
                        
                    </li>








                <?php endif; ?>

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                


                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                


                

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                

                

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
<script>
    function testClick()
    {
        if (document.documentElement.getAttribute("data-sidebar-size") === "sm-hover") {
            document.documentElement.setAttribute("data-sidebar-size", "sm-hover-active");
        } else if (document.documentElement.getAttribute("data-sidebar-size") === "sm-hover-active") {
            document.documentElement.setAttribute("data-sidebar-size", "sm-hover");
        } else {
            document.documentElement.setAttribute("data-sidebar-size", "sm-hover");
        }
    }
</script>
<?php /**PATH C:\xampp\htdocs\modern\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>