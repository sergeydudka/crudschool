<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 22.07.2018
 * Time: 14:56
 */

namespace crudschool\behaviors;


use crudschool\modules\alias\models\Alias;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\Inflector;

class AliasBehavior extends Behavior {
  /* @var $owner ActiveRecord */

  const ALIAS_MODEL = Alias::class;
  /* @var $alias Alias */
  private $alias;

  private $aliasField = 'alias';
  public $from;

  /**
   * @return array
   */
  public function events(): array {
    return [
      ActiveRecord::EVENT_BEFORE_VALIDATE => 'setAlias',
      ActiveRecord::EVENT_AFTER_INSERT    => 'saveAlias',
      ActiveRecord::EVENT_AFTER_FIND      => 'getAlias',
    ];
  }

  /**
   * @return mixed
   */
  public function getRefID() {
    /* @var $owner BaseActiveRecord */
    $owner = $this->owner;
    return $owner->getPrimaryKey();
  }

  /**
   *
   */
  public function saveAlias(): void {
    if ($this->alias) {
      $this->alias->ref_id = $this->owner->getPrimaryKey(FALSE);
      $this->alias->save();
    }
  }

  /**
   * @return bool
   */
  public function setAlias(): bool {
    $aliasID = $this->owner->alias_id;
    $code = trim($this->getPostAlias());
    $newCode = FALSE;
    if (!$code && $this->from && $this->owner[$this->from]) {
      $code = \crudschool\common\helpers\Transliteration::text($this->owner[$this->from], '-');

      $code = strtolower($code);
      $newCode = TRUE;
    }
    $alias = NULL;

    if ($aliasID) {
      $alias = Alias::findOne(['alias_id' => $aliasID]);
    } else {
      if ($code) {
        $alias = Alias::findOne(['code' => $code, 'ref_model' => get_class($this->owner)]);
        if ($alias && !$newCode) {
          $this->owner->addError($this->aliasField, "Code $code has already been taken by model " .
            get_class($this->owner) . ".");
          return FALSE;
        } else {
          if ($alias) {
            $code = '' . time();
          }
        }
      }
    }

    if ($alias && $code) {
      $alias->code = $code;
    } else {
      if ($code) {
        $alias = $this->createAlias($code);
      }
    }

    $alias->ref_id = $this->owner->getPrimaryKey(FALSE);

    $result = $alias->save();

    if (!$result) {
      $this->owner->addError($this->aliasField, implode('<br>', $alias->getFirstErrors()));
      return FALSE;
    }

    $this->owner->alias_id = $alias->alias_id;

    $this->alias = $alias;

    return TRUE;
  }

  /**
   * @param string $code
   * @return Alias
   */
  public function createAlias($code): Alias {
    return Alias::setAlias($this->getRefID(), get_class($this->owner), $code);
  }

  /**
   * @return string
   */
  public function getAlias(): string {
    return Alias::findOne(['alias_id' => $this->owner->alias_id])->code;
  }

  /**
   * @return string
   * @throws \ReflectionException
   */
  private function getPostAlias(): string {
    $post = \Yii::$app->request->post();
    $className = (new \ReflectionClass($this->owner))->getShortName();
    return $post[$className][$this->aliasField] ?? '';
  }
}