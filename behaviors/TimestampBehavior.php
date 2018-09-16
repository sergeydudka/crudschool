<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 04.07.2018
 * Time: 21:56
 */

namespace crudschool\behaviors;


use yii\rest\CreateAction;

class TimestampBehavior extends \yii\behaviors\TimestampBehavior {

  /**
   * @param $event
   * @return false|int|mixed|string
   */
  protected function getValue($event) {
    if ($this->value === NULL) {
      return date('Y-m-d H:i:s');
    }

    return parent::getValue($event);
  }
}