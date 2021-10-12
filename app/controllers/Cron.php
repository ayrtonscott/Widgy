<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

class Cron extends Controller {

    public function index() {

        /* Initiation */
        set_time_limit(0);

        /* Make sure the key is correct */
        if(!isset($_GET['key']) || (isset($_GET['key']) && $_GET['key'] != settings()->cron->key)) {
            die();
        }

        $date = \Altum\Date::$date;

        $this->users_plan_expiry_reminder();

        /* Delete old users logs */
        $ninety_days_ago_datetime = (new \DateTime())->modify('-90 days')->format('Y-m-d H:i:s');
        database()->query("DELETE FROM `users_logs` WHERE `datetime` < '{$ninety_days_ago_datetime}'");

        /* Update cron job last run date */
        $new_cron = json_encode(array_merge((array) settings()->cron, ['cron_datetime' => $date]));
        db()->where('`key`', 'cron')->update('settings', ['value' => $new_cron]);

        /* Make sure the reset date month is different than the current one to avoid double resetting */
        $reset_date = (new \DateTime(settings()->cron->reset_date))->format('m');
        $current_date = (new \DateTime())->format('m');

        if($reset_date != $current_date) {
            /* Reset the users notification impressions */
            database()->query("UPDATE `users` SET `current_month_notifications_impressions` = 0");

            $this->notifications_logs_cleanup();

            /* Update the settings with the updated time */
            $new_cron = json_encode(array_merge((array) settings()->cron, ['reset_date' => $date]));

            /* Database query */
            db()->where('`key`', 'cron')->update('settings', ['value' => $new_cron]);

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItem('settings');
        }
    }

    private function notifications_logs_cleanup() {

        /* Clean the track_logs table */
        $activity_date = (new \DateTime())->modify('-30 day')->format('Y-m-d H:i:s');
        database()->query("DELETE FROM `track_logs` WHERE `datetime` < '{$activity_date}'");

        /* Clean the track notifications table based on the users plan */
        $result = database()->query("SELECT `user_id`, `plan_settings` FROM `users` WHERE `active` = 1");

        /* Go through each result */
        while($user = $result->fetch_object()) {
            $user->plan_settings = json_decode($user->plan_settings);

            if($user->plan_settings->track_notifications_retention == -1) continue;

            /* Clear out old notification statistics logs */
            $x_days_ago_datetime = (new \DateTime())->modify('-' . ($row->plan_settings->track_notifications_retention ?? 90) . ' days')->format('Y-m-d H:i:s');
            database()->query("DELETE FROM `track_notifications` WHERE `datetime` < '{$x_days_ago_datetime}'");

            if(DEBUG) {
                echo sprintf('Track notifications cleanup done for user_id %s', $user->user_id);
            }
        }

    }

    private function users_plan_expiry_reminder() {

        /* Determine when to send the email reminder */
        $days = 5;
        $future_date = (new \DateTime())->modify('+' . $days . ' days')->format('Y-m-d H:i:s');

        $result = database()->query("
            SELECT
                `user_id`,
                `name`,
                `email`,
                `plan_id`,
                `plan_expiration_date`,
                `language`
            FROM 
                `users`
            WHERE 
                `active` = 1
                AND `plan_id` <> 'free'
                AND `plan_expiry_reminder` = '0'
                AND (`payment_subscription_id` IS NOT NULL OR `payment_subscription_id` <> '')
				AND '{$future_date}' > `plan_expiration_date`
            LIMIT 25
        ");

        /* Go through each result */
        while($user = $result->fetch_object()) {

            /* Determine the exact days until expiration */
            $days_until_expiration = (new \DateTime($user->plan_expiration_date))->diff((new \DateTime()))->days;

            /* Get the language for the user */
            $language = language($user->language);

            /* Prepare the email */
            $email_template = get_email_template(
                [
                    '{{DAYS_UNTIL_EXPIRATION}}' => $days_until_expiration,
                ],
                $language->global->emails->user_plan_expiry_reminder->subject,
                [
                    '{{DAYS_UNTIL_EXPIRATION}}' => $days_until_expiration,
                    '{{USER_PLAN_RENEW_LINK}}' => url('pay/' . $user->plan_id),
                    '{{NAME}}' => $user->name,
                    '{{PLAN_NAME}}' => (new \Altum\Models\Plan())->get_plan_by_id($user->plan_id)->name,
                ],
                $language->global->emails->user_plan_expiry_reminder->body
            );

            send_mail($user->email, $email_template->subject, $email_template->body);

            /* Update user */
            db()->where('user_id', $user->user_id)->update('users', ['plan_expiry_reminder' => 1]);

            if(DEBUG) {
                echo sprintf('Email sent for user_id %s', $user->user_id);
            }
        }

    }

}
