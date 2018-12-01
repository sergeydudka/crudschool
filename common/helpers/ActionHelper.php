<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 15.09.2018
 * Time: 18:06
 */

namespace crudschool\common\helpers;


class ActionHelper {
    const INDEX_ACTION_URL = "";
    const VIEW_ACTION_URL = "view";
    const UPDATE_ACTION_URL = "update";
    const CREATE_ACTION_URL = "create";
    const DELETE_ACTION_URL = "delete";

    const INDEX_ACTION_NAME = 'index';
    const VIEW_ACTION_NAME = 'view';
    const UPDATE_ACTION_NAME = 'update';
    const CREATE_ACTION_NAME = 'create';
    const DELETE_ACTION_NAME = 'delete';

    public static function getUrls() {
        return [
            self::INDEX_ACTION_NAME => self::INDEX_ACTION_URL,
            self::VIEW_ACTION_NAME => self::VIEW_ACTION_URL,
            self::UPDATE_ACTION_NAME => self::UPDATE_ACTION_URL,
            self::CREATE_ACTION_NAME => self::CREATE_ACTION_URL,
            self::DELETE_ACTION_NAME => self::DELETE_ACTION_URL,
        ];
    }

    public static function getActionUrl($action) {
        return self::getUrls()[$action] ?? strtolower($action);
    }
}