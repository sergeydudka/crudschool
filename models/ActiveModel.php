<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 23.09.2018
 * Time: 21:23
 */

namespace crudschool\models;

use yii\base\BaseObject;
use yii\base\ModelEvent;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class ActiveModel extends ActiveRecord {
    protected static $query = null;

    const EVENT_BEFORE_FIND = 'beforeFind';

    /**
     * @return BaseObject|ActiveQuery|null
     * @throws \yii\base\InvalidConfigException
     */
    public static function find() {
        self::$query = \Yii::createObject(ActiveQuery::class, [get_called_class()]);
        if (self::beforeFind()) {
            return self::$query;
        }
        return null;
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function beforeFind() {
        $event = new ModelEvent();
        $model = \Yii::createObject(get_called_class());
        $model->trigger(self::EVENT_BEFORE_FIND, $event);

        return $event->isValid;
    }

    /**
     * @return ActiveQuery
     */
    public static function getCurrentQuery() {
        return self::$query;
    }
}