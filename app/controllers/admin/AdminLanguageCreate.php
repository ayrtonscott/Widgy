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
use Altum\Logger;
use Altum\Middlewares\Csrf;
use Altum\Models\User;

class AdminLanguageCreate extends Controller {

    public function index() {

        /* Make sure to load up in memory the main language */
        Language::get(Language::$main_language);

        /* Default variables */
        $values = [];

        if(!empty($_POST)) {
            /* Clean some posted variables */
            $_POST['language'] = filter_var($_POST['language'], FILTER_SANITIZE_STRING);
            $_POST['language_code'] = mb_strtolower(filter_var($_POST['language_code'], FILTER_SANITIZE_STRING));

            $language_strings = '';
            $admin_language_strings = '';
            foreach(\Altum\Language::$language_objects[\Altum\Language::$default_language] as $key => $value) {
                $form_key = str_replace('.', '##', $key);

                if(isset($_POST[$form_key]) && !empty($_POST[$form_key])) {
                    $values[$form_key] = $_POST[$form_key];
                    $_POST[$form_key] = addcslashes($_POST[$form_key], "'");

                    if(mb_substr($key, 0, strlen('admin_')) == 'admin_') {
                        $admin_language_strings .= "'{$key}' => '{$_POST[$form_key]}',\n";
                    } else {
                        $language_strings .= "\t'{$key}' => '{$_POST[$form_key]}',\n";
                    }
                }
            }

            $language_content = function($language_strings) {
                return <<<ALTUM
<?php

return [
{$language_strings}
];
ALTUM;
            };

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            /* Check for any errors */
            $required_fields = ['language', 'language_code'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!is_writable(Language::$path)) {
                Alerts::add_error(sprintf(l('global.error_message.directory_not_writable'), Language::$path));
            }

            if(!is_writable(Language::$path . 'admin/')) {
                Alerts::add_error(sprintf(l('global.error_message.directory_not_writable'), Language::$path . 'admin/'));
            }

            if(in_array($_POST['language'], Language::$languages) || array_key_exists($_POST['language_code'], Language::$languages)) {
                Alerts::add_error(sprintf(l('admin_languages.error_message.language_exists'), $_POST['language'], $_POST['language_code']));
            }

            /* If there are no errors, continue */
            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                file_put_contents(Language::$path . $_POST['language'] . '#' . $_POST['language_code'] . '.php', $language_content($language_strings));
                file_put_contents(Language::$path . 'admin/' . $_POST['language'] . '#' . $_POST['language_code'] . '.php', $language_content($admin_language_strings));
                chmod(Language::$path . $_POST['language'] . '#' . $_POST['language_code'] . '.php', 0777);
                chmod(Language::$path . 'admin/' . $_POST['language'] . '#' . $_POST['language_code'] . '.php', 0777);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . filter_var($_POST['language'], FILTER_SANITIZE_STRING) . '</strong>'));

                /* Redirect */
                redirect('admin/language-update/' . $_POST['language_code']);
            }

        }

        /* Default variables */
        $values['language'] = $_POST['language'] ?? null;
        $values['language_code'] = $_POST['language_code'] ?? null;

        /* Main View */
        $data = [
            'values' => $values
        ];

        $view = new \Altum\Views\View('admin/language-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
