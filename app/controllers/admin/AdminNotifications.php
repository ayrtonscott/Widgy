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
use Altum\Middlewares\Csrf;
use Altum\Models\Model;

class AdminNotifications extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'campaign_id', 'type', 'is_enabled'], ['name'], ['datetime', 'name']));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `notifications` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/notifications?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $notifications = [];
        $notifications_result = database()->query("
            SELECT
                `notifications`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `notifications`
            LEFT JOIN
                `users` ON `notifications`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('notifications')}
                {$filters->get_sql_order_by('notifications')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $notifications_result->fetch_object()) {
            $notifications[] = $row;
        }

        /* Export handler */
        process_export_csv($notifications, 'include', ['notification_id', 'campaign_id', 'user_id', 'name', 'type', 'is_enabled', 'last_datetime', 'datetime'], sprintf(language()->admin_notifications->title));
        process_export_json($notifications, 'include', ['notification_id', 'campaign_id', 'user_id', 'name', 'type', 'is_enabled', 'last_datetime', 'datetime'], sprintf(language()->admin_notifications->title));

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'notifications' => $notifications,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('admin/notifications/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/notifications');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/notifications');
        }

        if(!isset($_POST['type']) || (isset($_POST['type']) && !in_array($_POST['type'], ['delete']))) {
            redirect('admin/notifications');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!Csrf::check()) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $notification_id) {
                        /* Delete the notification */
                        db()->where('notification_id', $notification_id)->delete('notifications');

                        /* Clear the cache */
                        \Altum\Cache::$adapter->deleteItemsByTag('notification_id=' . $notification_id);
                    }
                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(language()->admin_bulk_delete_modal->success_message);

        }

        redirect('admin/notifications');
    }

    public function delete() {

        $notification_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
        }

        if(!$notification = db()->where('notification_id', $notification_id)->getOne('notifications', ['notification_id'])) {
            redirect('admin/notifications');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the notification */
            db()->where('notification_id', $notification->notification_id)->delete('notifications');

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('notification_id=' . $notification->notification_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(language()->global->success_message->delete1, '<strong>' . $notification->name . '</strong>'));

        }

        redirect('admin/notifications');
    }

}
