<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;

class NotificationDataAjax extends Controller {

    public function index() {

        Authentication::guard();

        if(!empty($_POST) && (Csrf::check('token') || Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Create */
                case 'create': $this->create(); break;

                /* Delete */
                case 'delete': $this->delete(); break;

            }

        }

        die();
    }

    private function create() {
        $_POST['notification_id'] = (int) $_POST['notification_id'];
        $type = 'imported';

        /* Check for possible errors */
        if(!db()->where('notification_id', $_POST['notification_id'])->where('user_id', $this->user->user_id)->getValue('notifications', 'notification_id')) {
            $errors[] = true;
        }

        if(empty($_POST['key']) && empty($_POST['value'])) {
            $errors[] = true;
        }

        if(empty($errors)) {

            /* Parse the keys and values */
            $data = [];
            foreach($_POST['key'] as $key => $value) {

                if(!empty($_POST['key'][$key]) && isset($_POST['value'][$key])) {
                    $cleaned_value = Database::clean_string($value);

                    $data[$cleaned_value] = Database::clean_string($_POST['value'][$key]);
                }

            }

            $data = json_encode($data);

            /* Insert in the database */
            db()->insert('track_conversions', [
                'notification_id' => $_POST['notification_id'],
                'type' => $type,
                'data' => $data,
                'datetime' => \Altum\Date::$date
            ]);

            Response::json('', 'success');

        }
    }

    private function delete() {
        $_POST['id'] = (int) $_POST['id'];

        /* Check for possible errors */
        if(!$notification_id = db()->where('id', $_POST['id'])->getValue('track_conversions', 'notification_id')) {
            $errors[] = true;
        }

        if(!db()->where('notification_id', $notification_id)->where('user_id', $this->user->user_id)->getValue('notifications', 'notification_id')) {
            $errors[] = true;
        }

        if(empty($errors)) {

            /* Delete from database */
            db()->where('id', $_POST['id'])->delete('track_conversions');

            Response::json('', 'success');

        }
    }

}
