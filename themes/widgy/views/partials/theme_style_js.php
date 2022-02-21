<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<script>
    document.querySelectorAll('[data-choose-theme-style]').forEach(theme => {
        theme.addEventListener('click', event => {
            let chosen_theme_style = event.currentTarget.getAttribute('data-choose-theme-style');

            /* Set a cookie with the new theme style */
            set_cookie('theme_style', chosen_theme_style, 30, <?= json_encode(COOKIE_PATH) ?>);

            /* Change the css and button on the page */
            let css = document.querySelector(`#css_theme_style`);

            document.querySelector(`[data-theme-style]`).setAttribute('data-theme-style', chosen_theme_style);

            switch(chosen_theme_style) {
                case 'dark':
                    css.setAttribute('href', <?= json_encode(ASSETS_FULL_URL . 'css/' . (\Altum\Routing\Router::$path == 'admin' ? 'admin-' : null) . \Altum\ThemeStyle::$themes['dark'][l('direction')] . '?v=' . PRODUCT_CODE) ?>);
                    document.querySelector(`[data-choose-theme-style="dark"]`).classList.add('d-none');
                    document.querySelector(`[data-choose-theme-style="light"]`).classList.remove('d-none');
                    break;

                case 'light':
                    css.setAttribute('href', <?= json_encode(ASSETS_FULL_URL . 'css/' . (\Altum\Routing\Router::$path == 'admin' ? 'admin-' : null) . \Altum\ThemeStyle::$themes['light'][l('direction')] . '?v=' . PRODUCT_CODE) ?>);
                    document.querySelector(`[data-choose-theme-style="dark"]`).classList.remove('d-none');
                    document.querySelector(`[data-choose-theme-style="light"]`).classList.add('d-none');
                    break;
            }

            event.preventDefault();
        });
    })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
