<?php defined('ALTUMCODE') || die() ?>

<div class="container d-print-none">
    <div class="d-flex flex-column flex-lg-row justify-content-between mb-3">
        <div class="mb-3 mb-lg-0">
            <a class="h5" href="<?= url() ?>">
                <?php if(settings()->logo != ''): ?>
                    <img src="<?= UPLOADS_FULL_URL . 'logo/' . settings()->logo ?>" class="mb-2 footer-logo" alt="<?= l('global.accessibility.logo_alt') ?>" />
                <?php else: ?>
                    <span class="mb-2"><?= settings()->main->title ?></span>
                <?php endif ?>
            </a>
            <div><?= sprintf(l('global.footer.copyright'), date('Y'), settings()->main->title) ?></div>
        </div>

        <div class="d-flex flex-column flex-lg-row">
            <?php if(count(\Altum\Language::$active_languages) > 1): ?>
                <div class="dropdown mb-2 ml-lg-3">
                    <button type="button" class="btn btn-link text-decoration-none p-0" id="language_switch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-fw fa-language mr-1"></i> <?= l('global.language') ?></button>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="language_switch">
                        <h6 class="dropdown-header"><?= l('global.choose_language') ?></h6>
                        <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                            <a class="dropdown-item" href="<?= SITE_URL . $language_code . '/' . \Altum\Routing\Router::$original_request . '?set_language=' . $language_name ?>">
                                <?php if($language_name == \Altum\Language::$name): ?>
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
    </div>

    <div class="row">
        <div class="col-12 col-lg mb-3">
            <ul class="list-style-none d-flex flex-column flex-lg-row flex-wrap m-0">
                <?php if(settings()->email_notifications->contact && !empty(settings()->email_notifications->emails)): ?>
                    <li class="mb-2 mr-lg-3"><a href="<?= url('contact') ?>"><?= l('contact.menu') ?></a></li>
                <?php endif ?>

                <?php if(count($data->pages)): ?>
                    <?php foreach($data->pages as $row): ?>
                        <li class="mb-2 mr-lg-3"><a href="<?= $row->url ?>" target="<?= $row->target ?>"><?= $row->title ?></a></li>
                    <?php endforeach ?>
                <?php endif ?>
            </ul>
        </div>


        <div class="col-12 col-lg-auto">
            <div class="d-flex flex-wrap">
                <?php foreach(require APP_PATH . 'includes/admin_socials.php' as $key => $value): ?>
                    <?php if(isset(settings()->socials->{$key}) && !empty(settings()->socials->{$key})): ?>
                        <a href="<?= sprintf($value['format'], settings()->socials->{$key}) ?>" class="mr-2 mr-lg-0 ml-lg-2 mb-2" target="_blank" data-toggle="tooltip" title="<?= $value['name'] ?>"><i class="<?= $value['icon'] ?> fa-fw fa-lg"></i></a>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

