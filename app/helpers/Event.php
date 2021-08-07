<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum;

class Event {
    /* For events */
    public static $callbacks = [];

    /* For extra content, such as javascript */
    public static $content = [];

    public static function bind($event, Callable $function) {
        if(empty(self::$callbacks[$event]) || !is_array(self::$callbacks[$event])){
            self::$callbacks[$event] = [];
        }

        self::$callbacks[$event][] = $function;
    }

    public static function trigger() {
        $args = func_get_args();
        $event = $args[0];
        unset($args[0]);

        if (isset(self::$callbacks[$event])) {
            foreach(self::$callbacks[$event] as $func) {
                call_user_func_array($func, $args);
            }
        }
    }

    public static function add_content($content, $type) {

        if(isset(self::$content[$type])) {
            self::$content[$type][] = $content;
        } else {
            self::$content[$type] = [ $content ];
        }

    }

    public static function get_content($type) {

        $fullContent = '';

        if(isset(self::$content[$type])) {
            foreach (self::$content[$type] as $content) {

                $fullContent .= $content;

            }
        }

        return $fullContent;

    }
}
