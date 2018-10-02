<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 22.07.2018
 * Time: 14:56
 */

namespace crudschool\behaviors;


use crudschool\models\BaseModel;
use crudschool\modules\alias\models\Alias;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class AliasBehavior extends Behavior {
    /* @var $owner ActiveRecord */

    const ALIAS_MODEL = Alias::class;
    /* @var $alias Alias */
    private $alias;
    /* @var $owner ActiveRecord */
    public $owner;

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
    public function saveAlias() {
        if ($this->alias) {
            $this->alias->ref_id = $this->owner->getPrimaryKey(false);
            $this->alias->save();
        }
    }

    /**
     * @return bool
     */
    public function setAlias() {
        $aliasID = $this->owner->alias_id;
        $code = trim($this->getPostAlias());
        $newCode = false;
        if (!$code && $this->from && $this->owner[$this->from]) {
            $code = \crudschool\common\helpers\Transliteration::text($this->owner[$this->from], '-');

            $code = strtolower($code);
            $newCode = true;
        }
        $alias = null;

        if ($aliasID) {
            $alias = Alias::findOne(['alias_id' => $aliasID]);
        } else {
            if ($code) {
                $alias = Alias::findOne(['code' => $code, 'ref_model' => get_class($this->owner)]);
                if ($alias && !$newCode) {
                    $this->owner->addError($this->aliasField, "Code $code has already been taken by model " .
                        get_class($this->owner) . ".");
                    return false;
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

        $alias->ref_id = $this->owner->getPrimaryKey(false);

        $result = $alias->save();

        if (!$result) {
            $this->owner->addError($this->aliasField, implode('<br>', $alias->getFirstErrors()));
            return false;
        }

        $this->owner->alias_id = $alias->alias_id;

        $this->alias = $alias;

        return true;
    }

    /**
     * @param string $code
     * @return Alias
     */
    public function createAlias($code) {
        return Alias::setAlias($this->getRefID(), get_class($this->owner), $code);
    }

    /**
     * @return string
     */
    public function getAlias() {
        return Alias::findOne(['alias_id' => $this->owner->alias_id])->code;
    }


    /**
     * @return string
     * @throws \ReflectionException
     */
    private function getPostAlias() {
        $post = \Yii::$app->request->post();
        $className = (new \ReflectionClass($this->owner))->getShortName();
        return $post[$className][$this->aliasField] ?? '';
    }
}