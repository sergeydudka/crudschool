<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 15.09.2018
 * Time: 17:54
 */

namespace crudschool\common\helpers;


class ResponseHelper {

    public static function setJSONResponseFormat() {
        self::getResponse()->format = \yii\web\Response::FORMAT_JSON;
    }

    public static function setHTMLResponseFormat() {
        self::getResponse()->format = \yii\web\Response::FORMAT_HTML;
    }

    public static function getResponse() {
        return \Yii::$app->getResponse();
    }
}