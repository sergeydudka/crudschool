<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 15.09.2018
 * Time: 10:51
 */

namespace crudschool\common\helpers;


use crudschool\modules\editions\models\Edition;

class EditionHelper {
  /**
   * @return Edition
   */
  public static function getDefaultEdition() {
    return Edition::find()->where(['code' => \Yii::$app->language])->limit(1)->one();
  }

  /**
   * @param string $code
   * @return Edition
   */
  public static function getEditionByCode($code) {
    return Edition::find()->where(['code' => $code])->limit(1)->one();
  }

  /**
   * @param string $url
   * @return Edition|null
   */
  public static function getEditionByUrl($url) {
    return Edition::find()->where(['url' => $url])->limit(1)->one();
  }
}