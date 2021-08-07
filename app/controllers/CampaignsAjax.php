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
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;

class CampaignsAjax extends Controller {

    public function index() {

        Authentication::guard();

        if(!empty($_POST) && (Csrf::check('token') || Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Status toggle */
                case 'is_enabled_toggle': $this->is_enabled_toggle(); break;

                /* Custom Branding Set */
                case 'custom_branding': $this->custom_branding(); break;

                /* Create */
                case 'create': $this->create(); break;

                /* Update */
                case 'update': $this->update(); break;

            }

        }

        die();
    }

    private function is_enabled_toggle() {
        $_POST['campaign_id'] = (int) $_POST['campaign_id'];

        /* Get the current status */
        $is_enabled = db()->where('campaign_id', $_POST['campaign_id'])->getValue('campaigns', 'is_enabled');

        /* Update data in database */
        db()->where('campaign_id', $_POST['campaign_id'])->where('user_id', $this->user->user_id)->update('campaigns', [
            'is_enabled' => (int) !$is_enabled,
        ]);

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('campaign_id=' . $_POST['campaign_id']);

        Response::json('', 'success');
    }

    private function custom_branding() {
        $_POST['campaign_id'] = (int) $_POST['campaign_id'];
        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $_POST['url'] = trim(Database::clean_string($_POST['url']));

        /* Make sure the user has access to the custom branding method */
        if(!$this->user->plan_settings->custom_branding) {
            die();
        }

        /* Check for possible errors */
        if(!isset($_POST['name'], $_POST['url'])) {
            Response::json(language()->global->error_message->empty_fields, 'error');
        }

        $campaign_branding = json_encode([
            'name' => $_POST['name'],
            'url'   => $_POST['url']
        ]);

        /* Update data in database */
        db()->where('campaign_id', $_POST['campaign_id'])->where('user_id', $this->user->user_id)->update('campaigns', [
            'branding' => $campaign_branding,
        ]);

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('campaign_id=' . $_POST['campaign_id']);

        Response::json(language()->global->success_message->basic, 'success');
    }

    private function create() {
        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $_POST['include_subdomains'] = (int) isset($_POST['include_subdomains']);
        $is_enabled = 1;

        /* Domain checking */
        $pslManager = new \Pdp\PublicSuffixListManager();
        $parser = new \Pdp\Parser($pslManager->getList());
        $url = $parser->parseUrl(mb_strtolower($_POST['domain']));
        $punnnycode = new \TrueBV\Punycode();
        $_POST['domain'] = Database::clean_string($punnnycode->encode($url->getHost()));

        /* Check for possible errors */
        if(empty($_POST['name']) || empty($_POST['domain'])) {
            Response::json(language()->global->error_message->empty_fields, 'error');
        }

        /* Make sure that the user didn't exceed the limit */
        $account_total_campaigns = database()->query("SELECT COUNT(*) AS `total` FROM `campaigns` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total;
        if($this->user->plan_settings->campaigns_limit != -1 && $account_total_campaigns >= $this->user->plan_settings->campaigns_limit) {
            Response::json(language()->create_campaign_modal->error_message->campaigns_limit, 'error');
        }

        /* Generate an unique pixel key for the website */
        $pixel_key = string_generate(32);
        while(db()->where('pixel_key', $pixel_key)->getValue('campaigns', 'pixel_key')) {
            $pixel_key = string_generate(32);
        }

        /* Insert to database */
        $campaign_id = db()->insert('campaigns', [
            'user_id' => $this->user->user_id,
            'pixel_key' => $pixel_key,
            'name' => $_POST['name'],
            'domain' => $_POST['domain'],
            'include_subdomains' => $_POST['include_subdomains'],
            'is_enabled' => $is_enabled,
            'datetime' => Date::$date,
        ]);

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('campaign_id=' . $campaign_id);

        /* Set a nice success message */
        Response::json(sprintf(language()->global->success_message->create1, '<strong>' . htmlspecialchars($_POST['name']) . '</strong>'), 'success', ['campaign_id' => $campaign_id]);

    }

    private function update() {
        $_POST['campaign_id'] = (int) $_POST['campaign_id'];
        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $_POST['include_subdomains'] = (int) (bool) isset($_POST['include_subdomains']);

        /* Domain checking */
        $pslManager = new \Pdp\PublicSuffixListManager();
        $parser = new \Pdp\Parser($pslManager->getList());
        $url = $parser->parseUrl(mb_strtolower($_POST['domain']));
        $punnnycode = new \TrueBV\Punycode();
        $_POST['domain'] = Database::clean_string($punnnycode->encode($url->getHost()));

        /* Check for possible errors */
        if(empty($_POST['name']) || empty($_POST['domain'])) {
            Response::json(language()->global->error_message->empty_fields, 'error');
        }

        /* Insert to database */
        db()->where('campaign_id', $_POST['campaign_id'])->where('user_id', $this->user->user_id)->update('campaigns', [
            'name' => $_POST['name'],
            'domain' => $_POST['domain'],
            'include_subdomains' => $_POST['include_subdomains'],
            'last_datetime' => Date::$date,
        ]);

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('campaign_id=' . $_POST['campaign_id']);

        /* Set a nice success message */
        Response::json(sprintf(language()->global->success_message->update1, '<strong>' . htmlspecialchars($_POST['name']) . '</strong>'), 'success', ['campaign_id' => $campaign_id]);
    }

}
