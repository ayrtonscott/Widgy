<?php defined('ALTUMCODE') || die() ?>

(() => {
    let pixel_url_base = <?= json_encode(url()) ?>;
    let pixel_key = <?= json_encode($data->pixel_key) ?>;
    let pixel_analytics = <?= json_encode((bool) settings()->socialproofo->analytics_is_enabled) ?>;
    let pixel_css_loaded = false;

    /* Make sure to include the external css file */
    let link = document.createElement('link');
    link.href = '<?= ASSETS_FULL_URL . 'css/pixel.css' ?>';
    link.type = 'text/css';
    link.rel = 'stylesheet';
    link.media = 'screen,print';
    link.onload = function() { pixel_css_loaded = true };
    document.getElementsByTagName('head')[0].appendChild(link);

    /* Pixel header including all the needed libraries */
    <?php require_once ASSETS_PATH . 'js/pixel-header.js' ?>

    <?php

    foreach($data->notifications as $notification) {

        echo \Altum\Notification::get($notification->type, $notification, $data->user)->javascript;

    }
    ?>

    <?php require_once ASSETS_PATH . 'js/pixel-footer.js' ?>
})();
