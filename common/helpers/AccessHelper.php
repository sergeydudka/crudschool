<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 01.10.2018
 * Time: 20:53
 */

namespace crudschool\common\helpers;


use crudschool\modules\users\models\UserAccess;

class AccessHelper {
    /*
     * @var null|\yii\base\BaseObject|\yii\db\ActiveQuery
     * */
    private static $access = null;

    /**
     * @return null|\yii\base\BaseObject|\yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function getAccess() {
        if (self::$access === null) {
            if (\Yii::$app->getUser()->getIdentity()) {
                self::$access = UserAccess::find()->where(['user_group_id' => \Yii::$app->getUser()->getIdentity()
                                                                                        ->getGroup()])->all();
            }
        }

        return self::$access;
    }


    /**
     * @param string $modelName
     * @param string $actionName
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function getActionAccess($modelName, $actionName) {
        $access = self::getAccess();

        if (\Yii::$app->getUser()->getIdentity() && \Yii::$app->getUser()->getIdentity()->isAdmin()) {
            return true;
        }

        return $access[$modelName][$actionName] ?? false;
    }
}