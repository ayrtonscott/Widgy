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
        <link rel="stylesheet" href="themes/cartelitos/assets/font/iconsmind-s/css/iconsminds.css" />
        <link rel="stylesheet" href="themes/cartelitos/assets/font/simple-line-icons/css/simple-line-icons.css" />
        <link rel="stylesheet" href="themes/cartelitos/assets/css/vendor/bootstrap.min.css" />
        <link rel="stylesheet" href="themes/cartelitos/assets/css/dore.light.orangecarrot.min.css" />
        <link rel="stylesheet" href="themes/cartelitos/assets/css/main.css" />
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

        <a class="navbar-logo" href="Dashboard.Default.html">
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
                            <img alt="Profile Picture" src="<?= get_gravatar($this->user->email) ?>" />
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
                        <a href="<?= HELPCENTER_URL ?>">
                            <i class="iconsminds-library"></i>
                            <span><?= language()->custom->helpcenter ?></span>
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
        <script src="themes/cartelitos/assets/js/vendor/jquery-3.3.1.min.js"></script>
        <script src="themes/cartelitos/assets/js/vendor/bootstrap.bundle.min.js"></script>
        <script src="themes/cartelitos/assets/js/dore.script.js"></script>
        <script src="themes/cartelitos/assets/js/scripts.single.theme.js"></script>
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
</html>
<?php endif // * Agregado 19/12 en 10.0.0 (Eliminamos todo en el index) Fin. ?>