<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

class AdminIndex extends Controller {

    public function index() {

        $campaigns = db()->getValue('campaigns', 'count(`campaign_id`)');
        $notifications = db()->getValue('notifications', 'count(`notification_id`)');
        $track_notifications = db()->getValue('track_notifications', 'MAX(`id`)');
        $track_logs = db()->getValue('track_logs', 'MAX(`id`)');
        $track_conversions = db()->getValue('track_conversions', 'MAX(`id`)');
        $users = db()->getValue('users', 'count(`user_id`)');

        if(in_array(settings()->license->type, ['Extended License', 'extended'])) {
            $payments = db()->getValue('payments', 'count(`id`)');
            $payments_total_amount = db()->getValue('payments', 'sum(`total_amount`)');
        } else {
            $payments = $payments_total_amount = 0;
        }

        /* Main View */
        $data = [
            'campaigns' => $campaigns,
            'notifications' => $notifications,
            'track_notifications' => $track_notifications,
            'track_logs' => $track_logs,
            'track_conversions' => $track_conversions,
            'users' => $users,
            'payments' => $payments,
            'payments_total_amount' => $payments_total_amount,
        ];

        $view = new \Altum\Views\View('admin/index/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
