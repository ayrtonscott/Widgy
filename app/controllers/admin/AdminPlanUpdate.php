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
use Altum\Database\Database;
use Altum\Middlewares\Csrf;

class AdminPlanUpdate extends Controller {

    public function index() {

        $plan_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Make sure it is either the trial / free plan or normal plans */
        switch($plan_id) {

            case 'free':

                /* Get the current settings for the free plan */
                $plan = settings()->plan_free;

                break;

            case 'trial':

                /* Get the current settings for the trial plan */
                $plan = settings()->plan_trial;

                break;

            default:

                $plan_id = (int) $plan_id;

                /* Check if plan exists */
                if(!$plan = db()->where('plan_id', $plan_id)->getOne('plans')) {
                    redirect('admin/plans');
                }

                /* Parse the settings of the plan */
                $plan->settings = json_decode($plan->settings);

                /* Parse the taxes */
                $plan->taxes_ids = json_decode($plan->taxes_ids);

                if(in_array(settings()->license->type, ['Extended License', 'extended'])) {
                    /* Get the available taxes from the system */
                    $taxes = db()->get('taxes', null, ['tax_id', 'internal_name', 'name', 'description']);
                }

                break;

        }

        if(!empty($_POST)) {

            if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if (!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            /* Determine the enabled notifications */
            $enabled_notifications = [];

            foreach(array_keys(\Altum\Notification::get_config()) as $notification) {
                $enabled_notifications[$notification] = (bool) isset($_POST['enabled_notifications']) && in_array($notification, $_POST['enabled_notifications']);
            }

            /* Filter variables */
            $_POST['settings'] = [
                'no_ads' => (bool) isset($_POST['no_ads']),
                'removable_branding' => (bool) isset($_POST['removable_branding']),
                'custom_branding' => (bool) isset($_POST['custom_branding']),
                'api_is_enabled' => (bool) isset($_POST['api_is_enabled']),
                'affiliate_is_enabled' => (bool) isset($_POST['affiliate_is_enabled']),
                'campaigns_limit' => (int) $_POST['campaigns_limit'],
                'notifications_limit' => (int) $_POST['notifications_limit'],
                'notifications_impressions_limit' => (int) $_POST['notifications_impressions_limit'],
                'track_notifications_retention' => (int) $_POST['track_notifications_retention'],
                'enabled_notifications' => $enabled_notifications
            ];

            switch ($plan_id) {

                case 'free':

                    $_POST['name'] = Database::clean_string($_POST['name']);
                    $_POST['status'] = (int)$_POST['status'];

                    /* Make sure to not let the admin disable ALL the plans */
                    if(!$_POST['status']) {

                        $enabled_plans = (int) settings()->payment->is_enabled ? database()->query("SELECT COUNT(*) AS `total` FROM `plans` WHERE `status` = 1")->fetch_object()->total ?? 0 : 0;

                        if(!$enabled_plans && !settings()->plan_trial->status) {
                            Alerts::add_error(language()->admin_plan_update->error_message->disabled_plans);
                        }
                    }

                    $setting_key = 'plan_free';
                    $setting_value = json_encode([
                        'plan_id' => 'free',
                        'name' => $_POST['name'],
                        'days' => null,
                        'status' => $_POST['status'],
                        'settings' => $_POST['settings']
                    ]);

                    break;

                case 'trial':

                    $_POST['name'] = Database::clean_string($_POST['name']);
                    $_POST['days'] = (int)$_POST['days'];
                    $_POST['status'] = (int)$_POST['status'];

                    /* Make sure to not let the admin disable ALL the plans */
                    if(!$_POST['status']) {

                        $enabled_plans = (int) settings()->payment->is_enabled ? database()->query("SELECT COUNT(*) AS `total` FROM `plans` WHERE `status` = 1")->fetch_object()->total ?? 0 : 0;

                        if(!$enabled_plans && !settings()->plan_free->status) {
                            Alerts::add_error(language()->admin_plan_update->error_message->disabled_plans);
                        }
                    }

                    $setting_key = 'plan_trial';
                    $setting_value = json_encode([
                        'plan_id' => 'trial',
                        'name' => $_POST['name'],
                        'days' => $_POST['days'],
                        'status' => $_POST['status'],
                        'settings' => $_POST['settings']
                    ]);

                    break;

                default:

                    $_POST['name'] = Database::clean_string($_POST['name']);
                    $_POST['monthly_price'] = (float) $_POST['monthly_price'];
                    $_POST['annual_price'] = (float) $_POST['annual_price'];
                    $_POST['lifetime_price'] = (float) $_POST['lifetime_price'];
                    $_POST['status'] = (int) $_POST['status'];
                    $_POST['order'] = (int) $_POST['order'];
                    $_POST['taxes_ids'] = json_encode(array_keys($_POST['taxes_ids'] ?? []));

                    /* Make sure to not let the admin disable ALL the plans */
                    if(!$_POST['status']) {

                        $enabled_plans = (int) database()->query("SELECT COUNT(*) AS `total` FROM `plans` WHERE `status` = 1")->fetch_object()->total ?? 0;

                        if(
                            (
                                !$enabled_plans ||
                                ($enabled_plans == 1 && $plan->status))
                            && !settings()->plan_free->status
                            && !settings()->plan_trial->status
                        ) {
                            Alerts::add_error(language()->admin_plan_update->error_message->disabled_plans);
                        }
                    }

                    break;

            }


            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Update the plan in database */
                switch ($plan_id) {

                    case 'free':
                    case 'trial':

                        db()->where('`key`', $setting_key)->update('settings', ['value' => $setting_value]);

                        /* Clear the cache */
                        \Altum\Cache::$adapter->deleteItem('settings');

                        break;

                    default:

                        $settings = json_encode($_POST['settings']);

                        db()->where('plan_id', $plan_id)->update('plans', [
                            'name' => $_POST['name'],
                            'monthly_price' => $_POST['monthly_price'],
                            'annual_price' => $_POST['annual_price'],
                            'lifetime_price' => $_POST['lifetime_price'],
                            'settings' => $settings,
                            'taxes_ids' => $_POST['taxes_ids'],
                            'status' => $_POST['status'],
                            'order' => $_POST['order'],
                        ]);

                        break;

                }

                /* Update all users plan settings with these ones */
                if(isset($_POST['submit_update_users_plan_settings'])) {

                    $plan_settings = json_encode($_POST['settings']);

                    db()->where('plan_id', $plan_id)->update('users', ['plan_settings' => $plan_settings]);

                    /* Clear the cache */
                    \Altum\Cache::$adapter->deleteItemsByTag('users');

                }

                /* Set a nice success message */
                Alerts::add_success(sprintf(language()->global->success_message->update1, '<strong>' . $plan->name . '</strong>'));

                /* Refresh the page */
                redirect('admin/plan-update/' . $plan_id);

            }

        }

        /* Main View */
        $data = [
            'plan_id'    => $plan_id,
            'plan'       => $plan,
            'taxes'      => $taxes ?? null,
        ];

        $view = new \Altum\Views\View('admin/plan-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
