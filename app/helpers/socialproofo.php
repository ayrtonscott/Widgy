<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

function array_flatten($array, $prefix = '') {
    $result = [];

    foreach($array as $key=>$value) {
        if(is_array($value)) {
            $result = $result + array_flatten($value, $prefix . $key . '.');
        }
        else {
            $result[$prefix.$key] = $value;
        }
    }

    return $result;
}
