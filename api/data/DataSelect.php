<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 19.09.2018
 * Time: 20:05
 */

namespace crudschool\api\data;

use crudschool\common\helpers\JSONHelper;
use \yii\base\Model;

class DataSelect {
  private $model;
  private $selectAttributeName = 'select';
  private $select = [];

  /**
   * @param $params
   * @return bool
   */
  public function parse($params) {
    if (!isset($params[$this->selectAttributeName])) {
      return false;
    }

    $this->select = JSONHelper::parse($params[$this->selectAttributeName], true);
    return !!$this->select;
  }

  /**
   * @param Model|null $model
   * @return array|bool
   */
  public function build(Model $model = null) {
    if ($this->select) {
      if ($model) {
        $fields = $model->fields();
        return array_intersect($fields, array_keys($this->select));
      }
      return array_keys($this->select);
    }

    return false;
  }
}