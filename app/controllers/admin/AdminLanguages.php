<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Language;
use Altum\Middlewares\Csrf;
use Altum\Models\User;

class AdminLanguages extends Controller {

    public function index() {

        /* Main View */
        $view = new \Altum\Views\View('admin/languages/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function delete() {

        $language_code = isset($this->params[0]) ? $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!array_key_exists($language_code, Language::$languages)) {
            redirect('admin/languages');
        }

        $language = Language::$languages[$language_code];

        if(!Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!is_writable(Language::$path)) {
            Alerts::add_error(sprintf(l('global.error_message.directory_not_writable'), Language::$path));
        }

        if(!is_writable(Language::$path . 'admin/')) {
            Alerts::add_error(sprintf(l('global.error_message.directory_not_writable'), Language::$path . 'admin/'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the language */
            unlink(Language::$path . $language . '#' . $language_code . '.php');
            unlink(Language::$path . 'admin/' . $language . '#' . $language_code . '.php');

            /* Update all users that used this language */
            db()->where('language', $language)->update('users', ['language' => settings()->main->default_language]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $language . '</strong>'));

        }

        redirect('admin/languages');
    }

}
