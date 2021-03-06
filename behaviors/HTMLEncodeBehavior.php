<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 04.07.2018
 * Time: 21:49
 */

namespace crudschool\behaviors;


use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;

class HTMLEncodeBehavior extends Behavior {
  public $attributes;

  /**
   * @return array
   */
  public function events() {
    return [
      ActiveRecord::EVENT_BEFORE_INSERT => 'encode',
      ActiveRecord::EVENT_BEFORE_UPDATE => 'encode',
      ActiveRecord::EVENT_AFTER_FIND    => 'decode',
    ];
  }


  /**
   * @param $event
   */
  public function encode($event) {
    foreach ($this->attributes as $attribute) {
      $this->owner->$attribute = Html::encode($this->owner->$attribute);
    }
  }

  /**
   * @param $event
   */
  public function decode($event) {
    foreach ($this->attributes as $attribute) {
      $this->owner->$attribute = Html::decode($this->owner->$attribute);
    }
  }
}