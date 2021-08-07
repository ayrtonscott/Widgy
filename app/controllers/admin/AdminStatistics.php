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

class AdminStatistics extends Controller {
    public $type;
    public $datetime;

    public function index() {

        $this->type = isset($this->params[0]) && method_exists($this, $this->params[0]) ? Database::clean_string($this->params[0]) : 'growth';

        $this->datetime = \Altum\Date::get_start_end_dates_new();

        /* Process only data that is needed for that specific page */
        $type_data = $this->{$this->type}();

        /* Main View */
        $data = [
            'type' => $this->type,
            'datetime' => $this->datetime
        ];
        $data = array_merge($data, $type_data);

        $view = new \Altum\Views\View('admin/statistics/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    protected function growth() {

        $total = ['users' => 0, 'users_logs' => 0, 'redeemed_codes' => 0];

        /* Users */
        $users_chart = [];
        $result = database()->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`date`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                 `users`
            WHERE
                `date` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $users_chart[$row->formatted_date] = [
                'users' => $row->total
            ];

            $total['users'] += $row->total;
        }

        $users_chart = get_chart_data($users_chart);

        /* Users logs */
        $users_logs_chart = [];
        $result = database()->query("
            SELECT
                 COUNT(DISTINCT `user_id`) AS `total`,
                 DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                 `users_logs`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $users_logs_chart[$row->formatted_date] = [
                'users_logs' => $row->total
            ];

            $total['users_logs'] += $row->total;
        }

        $users_logs_chart = get_chart_data($users_logs_chart);

        /* Redeemed codes */
        if(in_array(settings()->license->type, ['Extended License', 'extended'])) {
            $redeemed_codes_chart = [];
            $result = database()->query("
                SELECT
                     COUNT(*) AS `total`,
                     DATE_FORMAT(`date`, '{$this->datetime['query_date_format']}') AS `formatted_date`
                FROM
                     `redeemed_codes`
                WHERE
                    `date` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
                GROUP BY
                    `formatted_date`
                ORDER BY
                    `formatted_date`
            ");
            while($row = $result->fetch_object()) {

                $row->formatted_date = $this->datetime['process']($row->formatted_date);

                $redeemed_codes_chart[$row->formatted_date] = [
                    'redeemed_codes' => $row->total
                ];

                $total['redeemed_codes'] += $row->total;
            }

            $redeemed_codes_chart = get_chart_data($redeemed_codes_chart);
        }

        return [
            'total' => $total,
            'users_chart' => $users_chart,
            'users_logs_chart' => $users_logs_chart,
            'redeemed_codes_chart' => $redeemed_codes_chart ?? null
        ];
    }

    protected function payments() {

        $total = ['total_amount' => 0, 'total_payments' => 0];

        $payments_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total_payments`, DATE_FORMAT(`date`, '{$this->datetime['query_date_format']}') AS `formatted_date`, TRUNCATE(SUM(`total_amount`), 2) AS `total_amount` FROM `payments` WHERE `date` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $payments_chart[$row->formatted_date] = [
                'total_amount' => $row->total_amount,
                'total_payments' => $row->total_payments
            ];

            $total['total_amount'] += $row->total_amount;
            $total['total_payments'] += $row->total_payments;
        }

        $payments_chart = get_chart_data($payments_chart);

        return [
            'total' => $total,
            'payments_chart' => $payments_chart
        ];

    }

    protected function affiliates_commissions() {

        $total = ['amount' => 0, 'total_affiliates_commissions' => 0];

        $affiliates_commissions_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total_affiliates_commissions`, DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`, TRUNCATE(SUM(`amount`), 2) AS `amount` FROM `affiliates_commissions` WHERE `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $affiliates_commissions_chart[$row->formatted_date] = [
                'amount' => $row->amount,
                'total_affiliates_commissions' => $row->total_affiliates_commissions
            ];

            $total['amount'] += $row->amount;
            $total['total_affiliates_commissions'] += $row->total_affiliates_commissions;
        }

        $affiliates_commissions_chart = get_chart_data($affiliates_commissions_chart);

        return [
            'total' => $total,
            'affiliates_commissions_chart' => $affiliates_commissions_chart
        ];

    }

    protected function affiliates_withdrawals() {

        $total = ['amount' => 0, 'total_affiliates_withdrawals' => 0];

        $affiliates_withdrawals_chart = [];
        $result = database()->query("SELECT COUNT(*) AS `total_affiliates_withdrawals`, DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`, TRUNCATE(SUM(`amount`), 2) AS `amount` FROM `affiliates_withdrawals` WHERE `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $affiliates_withdrawals_chart[$row->formatted_date] = [
                'amount' => $row->amount,
                'total_affiliates_withdrawals' => $row->total_affiliates_withdrawals
            ];

            $total['amount'] += $row->amount;
            $total['total_affiliates_withdrawals'] += $row->total_affiliates_withdrawals;
        }

        $affiliates_withdrawals_chart = get_chart_data($affiliates_withdrawals_chart);

        return [
            'total' => $total,
            'affiliates_withdrawals_chart' => $affiliates_withdrawals_chart
        ];

    }

    protected function campaigns() {

        $total = ['campaigns' => 0];

        /* Monitors */
        $campaigns_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `campaigns`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $campaigns_chart[$row->formatted_date] = [
                'campaigns' => $row->total
            ];

            $total['campaigns'] += $row->total;
        }

        $campaigns_chart = get_chart_data($campaigns_chart);

        return [
            'total' => $total,
            'campaigns_chart' => $campaigns_chart,
        ];

    }

    protected function notifications() {

        $total = ['notifications' => 0];

        /* Monitors */
        $notifications_chart = [];
        $result = database()->query("
            SELECT
                COUNT(*) AS `total`,
                DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                `notifications`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {
            $row->formatted_date = $this->datetime['process']($row->formatted_date);

            $notifications_chart[$row->formatted_date] = [
                'notifications' => $row->total
            ];

            $total['notifications'] += $row->total;
        }

        $notifications_chart = get_chart_data($notifications_chart);

        return [
            'total' => $total,
            'notifications_chart' => $notifications_chart,
        ];

    }

    protected function track_notifications() {

        $total = ['impression' => 0, 'hover' => 0, 'click' => 0, 'form_submission' => 0];

        /* Track notifications */
        $track_notifications_chart = [];
        $result = database()->query("    
            SELECT
                 `type`,
                 COUNT(`id`) AS `total`,
                 DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                 `track_notifications`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`,
                `type`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            /* Handle if the date key is not already set */
            if(!array_key_exists($row->formatted_date, $track_notifications_chart)) {
                $track_notifications_chart[$row->formatted_date] = [
                    'impression'        => 0,
                    'hover'             => 0,
                    'click'             => 0,
                    'form_submission'   => 0
                ];
            }

            $track_notifications_chart[$row->formatted_date][$row->type] = $row->total;

            $total[$row->type] += $row->total;
        }

        $track_notifications_chart = get_chart_data($track_notifications_chart);

        return [
            'total' => $total,
            'track_notifications_chart' => $track_notifications_chart,
        ];
    }

    protected function track_conversions() {

        $total = ['webhook' => 0, 'auto_capture' => 0, 'collector' => 0, 'imported' => 0];

        /* Track conversions */
        $track_conversions_chart = [];
        $result = database()->query("    
            SELECT
                 `type`,
                 COUNT(`id`) AS `total`,
                 DATE_FORMAT(`datetime`, '{$this->datetime['query_date_format']}') AS `formatted_date`
            FROM
                 `track_conversions`
            WHERE
                `datetime` BETWEEN '{$this->datetime['query_start_date']}' AND '{$this->datetime['query_end_date']}'
            GROUP BY
                `formatted_date`,
                `type`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            /* Handle if the date key is not already set */
            if(!array_key_exists($row->formatted_date, $track_conversions_chart)) {
                $track_conversions_chart[$row->formatted_date] = [
                    'webhook'        => 0,
                    'auto_capture'   => 0,
                    'collector'      => 0,
                    'imported'      => 0,
                ];
            }

            $track_conversions_chart[$row->formatted_date][$row->type] = $row->total;

            $total[$row->type] += $row->total;
        }

        $track_conversions_chart = get_chart_data($track_conversions_chart);


        return [
            'total' => $total,
            'track_conversions_chart' => $track_conversions_chart
        ];
    }

}
