<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 02.10.2018
 * Time: 20:53
 */

namespace crudschool\api\actions;


use MongoDB\Driver\Exception\AuthenticationException;
use yii\rest\Action;

class LogoutAction extends Action {
    public function run() {
        if (!\Yii::$app->getUser()->getIsGuest()) {
            \Yii::$app->getUser()->logout();
        }

        return null;
    }
}