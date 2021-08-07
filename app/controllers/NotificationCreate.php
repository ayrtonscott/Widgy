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
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Notification;

class NotificationCreate extends Controller {

    public function index() {

        Authentication::guard();

        $campaign_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Make sure the campaign exists and is accessible to the user */
        if(!$campaign = db()->where('campaign_id', $campaign_id)->where('user_id', $this->user->user_id)->getOne('campaigns')) {
            redirect('dashboard');
        }

        /* Make sure that the user didn't exceed the limit */
        $user_notifications_total = database()->query("SELECT COUNT(*) AS `total` FROM `notifications` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total;

        if($this->user->plan_settings->notifications_limit != -1 && $user_notifications_total >= $this->user->plan_settings->notifications_limit) {
            Alerts::add_error(language()->notification->error_message->notifications_limit);
            redirect('dashboard');
        }

        if(!empty($_POST)) {
            $_POST['type'] = trim(Database::clean_string($_POST['type']));
            $_POST['campaign_id'] = (int) $_POST['campaign_id'];
            $is_enabled = 0;

            /* Check for any errors */
            $required_fields = ['type', 'campaign_id'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]))) {
                    Alerts::add_field_error($field, language()->global->error_message->empty_field);
                }
            }

            /* If the notification settings is not set it means we got an invalid type */
            if(!Notification::get_config($_POST['type'])) {
                redirect('notification-create');
            }

            /* Check for possible errors */
            if(!db()->where('campaign_id', $_POST['campaign_id'])->where('user_id', $this->user->user_id)->getValue('campaigns', 'campaign_id')) {
                redirect('notification-create');
            }

            /* Check for permission of usage of the notification */
            if(!$this->user->plan_settings->enabled_notifications->{$_POST['type']}) {
                redirect('notification-create');
            }

            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                /* Determine the default settings */
                $notification_settings = json_encode(Notification::get_config($_POST['type']));
                $notification_key = md5($this->user->user_id . $_POST['campaign_id'] . $_POST['type'] . time());
                $name = language()->notification_create->default_name;

                /* Insert to database */
                $notification_id = db()->insert('notifications', [
                    'user_id' => $this->user->user_id,
                    'campaign_id' => $_POST['campaign_id'],
                    'name' => $name,
                    'type' => $_POST['type'],
                    'settings' => $notification_settings,
                    'notification_key' => $notification_key,
                    'is_enabled' => $is_enabled,
                    'datetime' => \Altum\Date::$date,
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(language()->global->success_message->create1, '<strong>' . htmlspecialchars($name) . '</strong>'));

                /* Redirect */
                redirect('notification/' . $notification_id);
            }
        }

        /* Prepare the View */
        $data = [
            'campaign' => $campaign,
            'notifications' => \Altum\Notification::get_config(),
        ];

        $view = new \Altum\Views\View('notification-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
