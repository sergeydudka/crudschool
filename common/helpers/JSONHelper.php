<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 19.09.2018
 * Time: 20:12
 */

namespace crudschool\common\helpers;


use yii\base\InvalidArgumentException;

class JSONHelper {

  /**
   * @param string $json
   * @param bool   $throwExeption
   * @throws InvalidArgumentException
   * @return bool|array
   */
  public static function parse($json, $throwExeption = false) {
    if ($json) {
      $json = json_decode($json, true);
      if (!$json && $throwExeption) {
        throw new InvalidArgumentException('Invalid JSON.');
      }
    }

    return (array)$json;
  }
}