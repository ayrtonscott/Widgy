<?php defined('ALTUMCODE') || die() ?>

<footer class="mt-5 d-flex flex-column flex-lg-row justify-content-between">
    <div class="mb-3 mb-lg-0">
        <div class="mb-2"><?= sprintf(l('global.footer.copyright'), date('Y'), settings()->main->title) ?></div>

        <div>Powered by <img src="<?= ASSETS_FULL_URL . 'images/altumcode.png' ?>" class="icon-favicon" alt="AltumCode logo" /> <a href="https://altumcode.com/" target="_blank">AltumCode</a>.</div>
    </div>

    <div class="d-flex flex-column flex-lg-row">
        <?php if(count(\Altum\Language::$languages) > 1): ?>
            <div class="dropdown mb-2 ml-lg-3">
                <button type="button" class="btn btn-link text-decoration-none p-0" id="language_switch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-fw fa-language mr-1"></i> <?= l('global.language') ?></button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="language_switch">
                    <h6 class="dropdown-header"><?= l('global.choose_language') ?></h6>
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

        <?php if(count(\Altum\ThemeStyle::$themes) > 1): ?>
            <div class="mb-2 ml-lg-3">
                <button type="button" data-choose-theme-style="dark" class="btn btn-link text-decoration-none p-0 <?= \Altum\ThemeStyle::get() == 'dark' ? 'd-none' : null ?>">
                    <i class="fa fa-fw fa-sm fa-moon mr-1"></i> <?= sprintf(l('global.theme_style'), l('global.theme_style_dark')) ?>
                </button>
                <button type="button" data-choose-theme-style="light" class="btn btn-link text-decoration-none p-0 <?= \Altum\ThemeStyle::get() == 'light' ? 'd-none' : null ?>">
                    <i class="fa fa-fw fa-sm fa-sun mr-1"></i> <?= sprintf(l('global.theme_style'), l('global.theme_style_light')) ?>
                </button>
            </div>

            <?php include_view(THEME_PATH . 'views/partials/theme_style_js.php') ?>
        <?php endif ?>
    </div>
</footer>
