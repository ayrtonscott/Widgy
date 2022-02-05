<?php // * Agregado 21/01 en 10.0.0 - (Agregamos Intercom) INICIO. ?>
<?php isset($this->user->user_id) ? $hash = hash_hmac('sha256', $this->user->user_id,'a5edqk3IEE_hhIILFborw4_7wRz1N9RroK9g-a0V') : "" ?>
<?php // * Agregado 21/01 en 10.0.0 - (Agregamos Intercom) FIN. ?>

<?php defined('ALTUMCODE') || die() ?>
<?php if(\Altum\Routing\Router::$controller_key != 'index'): // * Agregado 19/12 en 10.0.0 (Eliminamos todo en el index) INICIO. ?>
<!DOCTYPE html>
<html lang="<?= \Altum\Language::$language_code ?>" dir="<?= language()->direction ?>">
    <head>
        <title><?= \Altum\Title::get() ?></title>
        <base href="<?= SITE_URL; ?>">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="content-language" content="<?= \Altum\Language::$language_code  ?>" />

        <?php if(\Altum\Meta::$description): ?>
            <meta name="description" content="<?= \Altum\Meta::$description ?>" />
        <?php endif ?>
        <?php if(\Altum\Meta::$keywords): ?>
            <meta name="keywords" content="<?= \Altum\Meta::$keywords ?>" />
        <?php endif ?>

        <?php if(\Altum\Meta::$open_graph['url']): ?>
            <!-- Open Graph / Facebook / Twitter -->
            <?php foreach(\Altum\Meta::$open_graph as $key => $value): ?>
                <?php if($value): ?>
                    <meta property="og:<?= $key ?>" content="<?= $value ?>" />
                    <meta property="twitter:<?= $key ?>" content="<?= $value ?>" />
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>

        <?php if(!settings()->main->se_indexing): ?>
            <meta name="robots" content="noindex">
        <?php endif ?>

        <link rel="alternate" href="<?= SITE_URL . \Altum\Routing\Router::$original_request ?>" hreflang="x-default" />
        <?php if(count(\Altum\Language::$languages) > 1): ?>
            <?php foreach(\Altum\Language::$languages as $language_code => $language_name): ?>
                <?php if(settings()->default_language != $language_name): ?>
                    <link rel="alternate" href="<?= SITE_URL . $language_code . '/' . \Altum\Routing\Router::$original_request ?>" hreflang="<?= $language_code ?>" />
                <?php endif ?>
            <?php endforeach ?>
        <?php endif ?>

        <?php if(!empty(settings()->favicon)): ?>
            <link href="<?= UPLOADS_FULL_URL . 'favicon/' . settings()->favicon ?>" rel="shortcut icon" />
        <?php endif ?>

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap" rel="stylesheet">

        <link href="<?= ASSETS_FULL_URL . 'css/' . \Altum\ThemeStyle::get_file() . '?v=' . PRODUCT_CODE ?>" id="css_theme_style" rel="stylesheet" media="screen,print">
        <?php // * Agregado 17/12 en 10.0.0 - (Links a assets de dore) INICIO. ?>
        <link rel="stylesheet" href="themes/widgy/assets/font/iconsmind-s/css/iconsminds.css" />
        <link rel="stylesheet" href="themes/widgy/assets/css/vendor/perfect-scrollbar.css" />  <?php // * Agregado 24/1 en 10.0.0 - (Fix de Scroll) FIN. ?>
        <link rel="stylesheet" href="themes/widgy/assets/font/simple-line-icons/css/simple-line-icons.css" />
        <link rel="stylesheet" href="themes/widgy/assets/css/vendor/bootstrap.min.css" />
        <link rel="stylesheet" href="themes/widgy/assets/css/dore.light.orangecarrot.min.css" />
        <link rel="stylesheet" href="themes/widgy/assets/css/main.css" />
        <?php // * Agregado 17/12 en 10.0.0 - (Links a assets de dore) FIN. ?>

        <?php foreach(['custom.css', 'animate.min.css'] as $file): ?>
            <link href="<?= ASSETS_FULL_URL . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
        <?php endforeach ?>
        
        <?= \Altum\Event::get_content('head') ?>

        <?php if(!empty(settings()->custom->head_js)): ?>
            <?= settings()->custom->head_js ?>
        <?php endif ?>

        <?php if(!empty(settings()->custom->head_css)): ?>
            <style><?= settings()->custom->head_css ?></style>
        <?php endif ?>
    </head>


    <?php // * Agregado 17/12 en 10.0.0 - (Body de Dore) INICIO. ?>
    <?php if (\Altum\Routing\Router::$controller_key != "index" and \Altum\Routing\Router::$controller_key != "notfound") : ?>
    <body id="app-container" class="menu-default show-spinner">
    <nav class="navbar fixed-top">
        <div class="d-flex align-items-center navbar-left">
            <a href="#" class="menu-button d-none d-md-block">
                <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">
                    <rect x="0.48" y="0.5" width="7" height="1" />
                    <rect x="0.48" y="7.5" width="7" height="1" />
                    <rect x="0.48" y="15.5" width="7" height="1" />
                </svg>
                <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">
                    <rect x="1.56" y="0.5" width="16" height="1" />
                    <rect x="1.56" y="7.5" width="16" height="1" />
                    <rect x="1.56" y="15.5" width="16" height="1" />
                </svg>
            </a>

            <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
                    <rect x="0.5" y="0.5" width="25" height="1" />
                    <rect x="0.5" y="7.5" width="25" height="1" />
                    <rect x="0.5" y="15.5" width="25" height="1" />
                </svg>
            </a>

           
            <?php if(count(\Altum\Language::$languages) > 1): ?>
                        <div class="btn btn-sm btn-outline-dark d-none d-md-inline-block dropdown">
                            <a class="dropdown-toggle clickable" id="language_switch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-muted"></i> <?= language()->global->language ?></a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="language_switch">
                                <h6 class="dropdown-header"><?= language()->global->choose_language ?></h6>
                                <?php foreach(\Altum\Language::$languages as $language_code => $language_name): ?>
                                    <a class="dropdown-item" href="<?= SITE_URL . $language_code . '/' . \Altum\Routing\Router::$original_request . '?set_language=' . $language_name ?>">
                                        <?php if($language_name == \Altum\Language::$language): ?>
                                            <i class="fa fa-fw fa-sm fa-check mr-1 text-success"></i>
                                        <?php else: ?>
                                            <i class="fa fa-fw fa-sm fa-circle-notch mr-1 text-muted"></i>
                                        <?php endif ?>

                                        <?= $language_name ?>
                                    </a>
                                <?php endforeach ?>
                            </div>
                        </div>
                    <?php endif ?>

        </div>

        <a class="navbar-logo" href="dashboard">
            <span class="logo d-none d-xs-block"></span>
            <span class="logo-mobile d-block d-xs-none"></span>
        </a>

        <div class="navbar-right">
            <div class="header-icons d-inline-block align-middle">
                
                <button class="header-icon btn btn-empty d-none d-sm-inline-block" type="button" id="fullScreenButton">
                    <i class="simple-icon-size-fullscreen"></i>
                    <i class="simple-icon-size-actual"></i>
                </button>

            </div>

            <div class="user d-inline-block">
                <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <span class="name"><?= $this->user->name ?></span>
                    <span>
                        <?php // * INICIO - Eliminado el 25/12 en 10.0.0 - Borramos la función de gravatar ?>
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="32" height="32" viewBox="0 0 172 172" style=" fill:#000000;"><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,172v-172h172v172z" fill="none" stroke="none"></path><g stroke="none"><path d="M86,88.15c-22.5234,0 -40.85,-18.3266 -40.85,-40.85c0,-22.5234 18.3266,-40.85 40.85,-40.85c22.5234,0 40.85,18.3266 40.85,40.85c0,22.5234 -18.3266,40.85 -40.85,40.85z" fill="#efa169"></path><path d="M86,8.6c21.3409,0 38.7,17.3591 38.7,38.7c0,21.3409 -17.3591,38.7 -38.7,38.7c-21.3409,0 -38.7,-17.3591 -38.7,-38.7c0,-21.3409 17.3591,-38.7 38.7,-38.7M86,4.3c-23.7489,0 -43,19.2511 -43,43c0,23.7489 19.2511,43 43,43c23.7489,0 43,-19.2511 43,-43c0,-23.7489 -19.2511,-43 -43,-43z" fill="#ffe6d2"></path><g><path d="M10.75,165.55v-9.8857c0,-32.7144 47.9751,-45.107 51.6903,-46.0143h47.1237c3.7109,0.9073 51.686,13.2956 51.686,46.0143v9.8857z" fill="#e3f3ff"></path><path d="M109.3017,111.8c4.9579,1.2427 49.7983,13.3558 49.7983,43.8643v7.7357h-146.2v-7.7357c0,-30.5085 44.8404,-42.6216 49.7983,-43.8643h46.6034M109.8134,107.5h-47.6268c0,0 -53.5866,12.1561 -53.5866,48.1643c0,0 0,5.1772 0,12.0357h154.8c0,-6.8585 0,-12.0357 0,-12.0357c0,-36.0082 -53.5866,-48.1643 -53.5866,-48.1643z" fill="#b9d0e8"></path></g></g><g stroke="none"><g id="Layer_1"><g><path d="M130.6297,169.85l-1.548,-9.2837l-1.3201,-0.3655c-4.5408,-1.2599 -8.7118,-3.6636 -12.0701,-6.9531l-0.9761,-0.9589l-8.7978,3.2981l-4.6311,-8.0152l7.267,-5.9856l-0.3397,-1.3244c-0.602,-2.3521 -0.9073,-4.6956 -0.9073,-6.9617c0,-2.2661 0.3053,-4.6096 0.9116,-6.9574l0.3397,-1.3244l-7.267,-5.9856l4.6311,-8.0152l8.7978,3.2981l0.9718,-0.9632c3.354,-3.2852 7.525,-5.6889 12.0701,-6.9531l1.3201,-0.3655l1.548,-9.2837h9.2536l1.548,9.2837l1.3201,0.3655c4.5451,1.2642 8.7161,3.6679 12.0701,6.9531l0.9761,0.9589l8.7978,-3.2981l4.6311,8.0152l-7.267,5.9856l0.3397,1.3244c0.602,2.3521 0.9073,4.6956 0.9073,6.9617c0,2.2661 -0.3053,4.6096 -0.9116,6.9574l-0.3397,1.3244l7.267,5.9856l-4.6311,8.0152l-8.7978,-3.2981l-0.9761,0.9589c-3.354,3.2895 -7.5293,5.6932 -12.0701,6.9531l-1.3201,0.3655l-1.548,9.2837h-9.2493zM135.2565,118.25c-8.299,0 -15.05,6.751 -15.05,15.05c0,8.299 6.751,15.05 15.05,15.05c8.299,0 15.05,-6.751 15.05,-15.05c0,-8.299 -6.751,-15.05 -15.05,-15.05z" fill="#dcd5f2"></path><path d="M138.0644,98.9l1.0234,6.1318l0.4515,2.7004l2.6402,0.7353c4.1925,1.1653 8.0453,3.3841 11.137,6.4199l1.9565,1.9178l2.5628,-0.9589l5.8136,-2.1758l2.8079,4.8633l-4.8074,3.956l-2.1156,1.7372l0.6794,2.6488c0.559,2.1801 0.8428,4.343 0.8428,6.4242c0,2.0812 -0.2838,4.2441 -0.8428,6.4242l-0.6794,2.6488l2.1113,1.7372l4.8074,3.956l-2.8079,4.8633l-5.8136,-2.1758l-2.5628,-0.9589l-1.9565,1.9178c-3.0917,3.0315 -6.9445,5.2546 -11.137,6.4199l-2.6402,0.7353l-0.4515,2.7004l-1.0191,6.1318h-5.6158l-1.0234,-6.1318l-0.4515,-2.7004l-2.6402,-0.7353c-4.1925,-1.1653 -8.0453,-3.3841 -11.137,-6.4199l-1.9565,-1.9178l-2.5585,0.9589l-5.8136,2.1758l-2.8079,-4.8633l4.8074,-3.956l2.1113,-1.7372l-0.6794,-2.6488c-0.559,-2.1801 -0.8428,-4.343 -0.8428,-6.4242c0,-2.0812 0.2838,-4.2441 0.8428,-6.4242l0.6794,-2.6488l-2.1113,-1.7372l-4.8074,-3.956l2.8079,-4.8633l5.8136,2.1758l2.5628,0.9589l1.9565,-1.9178c3.0917,-3.0315 6.9445,-5.2546 11.137,-6.4199l2.6402,-0.7353l0.4515,-2.7004l1.0191,-6.1318h5.6158M135.2565,150.5c9.4858,0 17.2,-7.7142 17.2,-17.2c0,-9.4858 -7.7142,-17.2 -17.2,-17.2c-9.4858,0 -17.2,7.7142 -17.2,17.2c0,9.4858 7.7142,17.2 17.2,17.2M141.7065,94.6h-12.9l-1.6211,9.7266c-4.9665,1.3803 -9.417,3.9818 -12.9946,7.4906l-9.2235,-3.4529l-6.45,11.1714l7.6196,6.2737c-0.6192,2.3994 -0.9804,4.8977 -0.9804,7.4906c0,2.5929 0.3612,5.0912 0.9761,7.4906l-7.6196,6.2694l6.45,11.1714l9.2235,-3.4572c3.5776,3.5088 8.0281,6.1103 12.9946,7.4906l1.6254,9.7352h12.9l1.6211,-9.7266c4.9665,-1.3803 9.417,-3.9818 12.9946,-7.4906l9.2235,3.4572l6.45,-11.1714l-7.6196,-6.2694c0.6192,-2.408 0.9804,-4.9063 0.9804,-7.4992c0,-2.5929 -0.3612,-5.0912 -0.9761,-7.4906l7.6196,-6.2737l-6.45,-11.1714l-9.2235,3.4572c-3.5776,-3.5088 -8.0281,-6.1103 -12.9946,-7.4906l-1.6254,-9.7309zM135.2565,146.2c-7.1251,0 -12.9,-5.7749 -12.9,-12.9c0,-7.1251 5.7749,-12.9 12.9,-12.9c7.1251,0 12.9,5.7749 12.9,12.9c0,7.1251 -5.7749,12.9 -12.9,12.9z" fill="#8b75a1"></path></g></g></g><path d="M98.513,172v-77.4h73.487v77.4z" id="overlay-drag" fill="#ff0000" stroke="none" opacity="0"></path></g></svg>
                        <?php // * FIN - Eliminado el 25/12 en 10.0.0 - Borramos la función de gravatar ?>             
                    </span>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                            <?php if(\Altum\Middlewares\Authentication::is_admin()): ?>
                                <a class="dropdown-item" href="<?= url('admin') ?>"><i class="fa fa-fw fa-sm fa-user-shield mr-1"></i> <?= language()->global->menu->admin ?></a>
                            <?php endif ?>
                            <a class="dropdown-item" href="<?= url('account') ?>"><i class="fa fa-fw fa-sm fa-wrench mr-1"></i> <?= language()->account->menu ?></a>
                            <a class="dropdown-item" href="<?= url('account-plan') ?>"><i class="fa fa-fw fa-sm fa-box-open mr-1"></i> <?= language()->account_plan->menu ?></a>

                            <?php if(settings()->payment->is_enabled): ?>
                            <a class="dropdown-item" href="<?= url('account-payments') ?>"><i class="fa fa-fw fa-sm fa-dollar-sign mr-1"></i> <?= language()->account_payments->menu ?></a>

                                <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
                                    <a class="dropdown-item" href="<?= url('referrals') ?>"><i class="fa fa-fw fa-sm fa-wallet mr-1"></i> <?= language()->referrals->menu ?></a>
                                <?php endif ?>
                            <?php endif ?>

                            <a class="dropdown-item" href="<?= url('account-api') ?>"><i class="fa fa-fw fa-sm fa-code mr-1"></i> <?= language()->account_api->menu ?></a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= url('logout') ?>"><i class="fa fa-fw fa-sm fa-sign-out-alt mr-1"></i> <?= language()->global->menu->logout ?></a>
                        </div>
            </div>
        </div>
    </nav>
    <div class="menu">
        <div class="main-menu">
            <div class="scroll">
                <ul class="list-unstyled">
                    <li class="<?= \Altum\Routing\Router::$controller_key == 'dashboard' ? 'active' : null ?>">
                        <a href="<?= url('dashboard') ?>">
                            <i class="iconsminds-shop-4"></i>
                            <span><?= language()->dashboard->menu ?></span>
                        </a>
                    </li>
                    <li class="<?= \Altum\Routing\Router::$controller_key == 'account' ? 'active' : null ?>">
                        <a href="<?= url('account') ?>">
                            <i class="iconsminds-profile"></i>
                            <span><?= language()->account->menu ?></span>
                        </a>
                    </li>
                    <li class="<?= \Altum\Routing\Router::$controller_key == 'account-plan' ? 'active' : null ?>">
                        <a href="<?= url('account-plan') ?>">
                            <i class="iconsminds-upgrade"></i>
                            <span><?= language()->account_plan->menu ?></span>
                        </a>
                    </li>
                    <li class="<?= \Altum\Routing\Router::$controller_key == 'account-payments' ? 'active' : null ?>">
                        <a href="<?= url('account-payments') ?>">
                            <i class="iconsminds-coins"></i>
                            <span><?= language()->account_payments->menu ?></span>
                        </a>
                    </li>
                    <li class="<?= \Altum\Routing\Router::$controller_key == 'account-logs' ? 'active' : null ?>">
                        <a href="<?= url('account-logs') ?>">
                            <i class="iconsminds-finger-print"></i>
                            <span><?= language()->account_logs->menu ?></span>
                        </a>
                    </li>
                    <li class="">
                        <a href="<?= HELPCENTER_URL ?><?= \Altum\Language::$language_code == "tn" ? "es" : \Altum\Language::$language_code // * Agregado 24/1 en 10.0.0 (Fix language code) INICIO. ?> ">
                            <i class="iconsminds-library"></i>
                            <span><?= language()->custom->helpcenter ?> </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <footer class="page-footer">
        <div class="footer-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <p class="mb-0 text-muted"><?= sprintf(language()->global->footer->copyright, date('Y'), settings()->title) ?></p>
                    </div>
                    <div class="col-sm-6 d-none d-sm-block">
                        <ul class="breadcrumb pt-0 pr-0 float-right">
                            <li class="breadcrumb-item mb-0">
                                <a href="#" class="btn-link">Reviews</a>
                            </li>
                            <li class="breadcrumb-item mb-0">
                                <a href="#" class="btn-link">Purchase</a>
                            </li>
                            <li class="breadcrumb-item mb-0">
                                <a href="#" class="btn-link">Docs</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    </body>
    <?php endif ?>
    <?php // * Agregado 17/12 en 10.0.0 - (Body de Dore) FIN. ?>

    <body class="<?= language()->direction == 'rtl' ? 'rtl' : null ?> <?= \Altum\Routing\Router::$controller_settings['body_white'] ? 'bg-white' : null ?>" data-theme-style="<?= \Altum\ThemeStyle::get() ?>">
        <?php //ALTUMCODE:DEMO if(DEMO) echo include_view(THEME_PATH . 'views/partials/ac_banner.php', ['demo_url' => 'https://socialproofo.com/demo/', 'title_text' => 'SocialProofo by AltumCode', 'product_url' => 'https://altumco.de/socialproofo-buy', 'buy_text' => 'Buy SocialProofo']) ?>

        <?php require THEME_PATH . 'views/partials/announcements.php' ?>

        <?php // * Eliminado 17/12 en 10.0.0 - (Sacamos el view menu) INICIO - FIN. ?>

        <main class="">

            <?= $this->views['content'] ?>

        </main>
        

        <?php if(\Altum\Routing\Router::$controller_key != 'index'): ?>
            <?php require THEME_PATH . 'views/partials/ads_footer.php' ?>
        <?php endif ?>

        <?php // * Eliminado 17/12 en 10.0.0 - (Sacamos el footer) INICIO - FIN. ?>

        <?= \Altum\Event::get_content('modals') ?>

        <?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

        <?php // * Agregado 17/12 en 10.0.0 - (Links a assets de dore) INICIO. ?>
        <script src="themes/widgy/assets/js/vendor/jquery-3.3.1.min.js"></script>
        <script src="themes/widgy/assets/js/vendor/perfect-scrollbar.min.js"></script>  <?php // * Agregado 24/1 en 10.0.0 - (Fix de Scroll) FIN. ?>
        <script src="themes/widgy/assets/js/vendor/bootstrap.bundle.min.js"></script>
        <script src="themes/widgy/assets/js/dore.script.js"></script>
        <script src="themes/widgy/assets/js/scripts.single.theme.js"></script>
        <?php // * Agregado 17/12 en 10.0.0 - (Links a assets de dore) FIN. ?>

        <?php // * Modificado 17/12 en 10.0.0 - (Eliminamos algunas cosas) INICIO.
        /*  Original - (3 Lineas)
            // <?php foreach(['libraries/jquery.min.js', 'libraries/popper.min.js', 'libraries/bootstrap.min.js', 'main.js', 'functions.js', 'libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.modified.js'] as $file): ?>
            // <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
            // <?php endforeach ?> 
        */ ?>

        <?php foreach (['libraries/popper.min.js', 'main.js', 'functions.js', 'libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.modified.js'] as $file) : ?>
            <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
        <?php endforeach ?>
        <?php // * Modificado 17/12 en 10.0.0 - (Eliminamos algunas cosas) Fin. ?>
        <?php endif // * Agregado 19/12 en 10.0.0 (Eliminamos todo en el index) Fin. ?>
        <?= \Altum\Event::get_content('javascript') ?>
<?php if(\Altum\Routing\Router::$controller_key != 'index'): // * Agregado 19/12 en 10.0.0 (Eliminamos todo en el index) INICIO. ?>
    </body>

    <?php // * Agregado 21/01 en 10.0.0 - (Agregamos Intercom) INICIO. ?>
    <script>
        window.intercomSettings = {
            app_id: "nds4bz0l",
            name: "<?= isset($this->user->name) ? $this->user->name : "" ?>",
            language: "<?= isset($this->user->language) ? $this->user->language : "" ?>",
            user_id: "<?= isset($this->user->user_id) ? $this->user->user_id : "" ?>",
            email: "<?= isset($this->user->email) ? $this->user->email : "" ?>",
            user_hash: "<?= isset($hash) ? $hash : "" ?>",
            executive: "<?= isset($this->user->plan_settings->no_ads) ? $this->user->plan_settings->no_ads : "" ?>",
            created_at: "<?= isset($this->user->datetime) ? strtotime($this->user->datetime) : "" ?>",
            plan: "<?= isset($this->user->plan_id) ? $this->user->plan_id : "" ?>",
            trial: "<?= isset($this->user->plan_trial_done) ? $this->user->plan_trial_done : "" ?>",
            current_impressions: "<?= isset($this->user->current_month_notifications_impressions) ? $this->user->current_month_notifications_impressions : "" ?>",
            impressions_limit: "<?= isset($this->user->plan_settings->notifications_impressions_limit) ? $this->user->plan_settings->notifications_impressions_limit :"" ?>",
            Vencimiento: "<?= isset($this->user->plan_expiration_date) ? $this->user->plan_expiration_date : "" ?>"
        };
    </script>
    <script>
    // We pre-filled your app ID in the widget URL: 'https://widget.intercom.io/widget/nds4bz0l'
    (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',w.intercomSettings);}else{var d=document;var i=function(){i.c(arguments);};i.q=[];i.c=function(args){i.q.push(args);};w.Intercom=i;var l=function(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/nds4bz0l';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);};if(document.readyState==='complete'){l();}else if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
    </script>
    <?php // * Agregado 21/01 en 10.0.0 - (Agregamos Intercom) FIN. ?>

</html>
<?php endif // * Agregado 19/12 en 10.0.0 (Eliminamos todo en el index) Fin. ?>