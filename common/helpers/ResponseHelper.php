<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 15.09.2018
 * Time: 17:54
 */

namespace crudschool\common\helpers;


class ResponseHelper {
  /**
   *
   */
  public static function setJSONResponseFormat() {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
  }
}