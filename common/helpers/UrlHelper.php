<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 04.11.2018
 * Time: 17:00
 */

namespace crudschool\common\helpers;


class UrlHelper {
    const DEV_MODULES = ['gii', 'debug'];

    public static function isDevModuleUrl($url) {
        $parts = explode('/', trim($url, '/'));
        return (bool)array_intersect($parts, self::DEV_MODULES);
    }
}