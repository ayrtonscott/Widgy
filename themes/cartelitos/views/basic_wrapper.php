<?php defined('ALTUMCODE') || die() ?>
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
        <?php foreach(['custom.css', 'animate.min.css', 'pixel.css'] as $file): ?>
            <link href="<?= ASSETS_FULL_URL . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen">
        <?php endforeach ?>

        <?php // * Agregado 17/12 en 10.0.0 - (Links a assets de dore) INICIO. ?>
        <link rel="stylesheet" href="themes/cartelitos/assets/font/simple-line-icons/css/simple-line-icons.css" />
        <link rel="stylesheet" href="themes/cartelitos/assets/css/vendor/bootstrap.min.css" />
        <?php // * Agregado 17/12 en 10.0.0 - (Links a assets de dore) FIN. ?>

        <?= \Altum\Event::get_content('head') ?>

        <?php if(!empty(settings()->custom->head_js)): ?>
            <?= settings()->custom->head_js ?>
        <?php endif ?>

        <?php if(!empty(settings()->custom->head_css)): ?>
            <style><?= settings()->custom->head_css ?></style>
        <?php endif ?>
    </head>

    <body class="<?= language()->direction == 'rtl' ? 'rtl' : null ?> <?= \Altum\Routing\Router::$controller_settings['body_white'] ? 'bg-white' : null ?>">
    <?php require THEME_PATH . 'views/partials/announcements.php' ?>

        <main class="py-5" data-theme-style="<?= \Altum\ThemeStyle::get() ?>">

            <div class="container mb-5">
                <div class="d-flex justify-content-center">
                    <a href="<?= url() ?>">
                        <?php if(settings()->logo != ''): ?>
                            <img src="<?= UPLOADS_FULL_URL . 'logo/' . settings()->logo ?>" class="img-fluid navbar-logo" alt="<?= language()->global->accessibility->logo_alt ?>" />
                        <?php else: ?>
                            <h1><?= settings()->title ?></h1>
                        <?php endif ?>
                    </a>
                </div>
            </div>

            <?= $this->views['content'] ?>

        </main>

        <?php if(\Altum\Routing\Router::$controller_key != 'index'): ?>
            <?php require THEME_PATH . 'views/partials/ads_footer.php' ?>
        <?php endif ?>

        <?= \Altum\Event::get_content('modals') ?>

        <?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

        <?php // * Agregado 17/12 en 10.0.0 - (Links a assets de dore) INICIO. ?>
        <script src="themes/cartelitos/assets/js/vendor/jquery-3.3.1.min.js"></script>
        <script src="themes/cartelitos/assets/js/vendor/bootstrap.bundle.min.js"></script>
        <script src="themes/cartelitos/assets/js/dore.script.js"></script>
        <script src="themes/cartelitos/assets/js/scripts.single.theme.js"></script>
        <?php // * Agregado 17/12 en 10.0.0 - (Links a assets de dore) FIN. ?>

        <?php // * Modificado 17/12 en 10.0.0 - (Eliminamos algunas cosas) INICIO.
        /* Original: (3 Lineas)
        //<?php foreach(['libraries/jquery.min.js', 'libraries/popper.min.js', 'libraries/bootstrap.min.js', 'main.js', 'functions.js', 'libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.modified.js'] as $file): ?>
        //<script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
        //<?php endforeach ?>
        */ ?>
        <?php foreach(['libraries/popper.min.js', 'main.js', 'functions.js', 'libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.modified.js'] as $file): ?>
            <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
        <?php endforeach ?>
        <?php // * Modificado 17/12 en 10.0.0 - (Eliminamos algunas cosas) FIN. ?>

        <?= \Altum\Event::get_content('javascript') ?>
    </body>
</html>
