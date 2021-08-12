<?php

namespace Altum\Plugin;

class ProNotifications {
    public static $plugin_id = 'pro-notifications';

    public static function install() {

        /* Run the installation process of the plugin */
        $queries = [];

        foreach($queries as $query) {
            database()->query($query);
        }

        return self::save_status(1);

    }

    public static function uninstall() {

        /* Run the installation process of the plugin */
        $queries = [];

        foreach($queries as $query) {
            database()->query($query);
        }

        return self::save_status(-1);

    }

    public static function activate() {
        return self::save_status(1);
    }

    public static function disable() {
        return self::save_status(0);
    }

    private static function save_status($new_status) {

        /* Enable the plugin from the config file */
        $new_config = clone \Altum\Plugin::get(self::$plugin_id);
        unset($new_config->path);
        $new_config->status = $new_status;

        /* Save the new config file */
        $config_saved = file_put_contents(\Altum\Plugin::get(self::$plugin_id)->path . 'config.json', json_encode($new_config));

        return (bool) $config_saved;

    }

}
