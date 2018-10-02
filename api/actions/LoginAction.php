<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 02.10.2018
 * Time: 20:48
 */

namespace crudschool\api\actions;

use crudschool\models\BaseUser;
use yii\rest\Action;

class LoginAction extends Action {
    /**
     * @return BaseUser|null|\yii\web\IdentityInterface
     * @throws \Throwable
     */
    public function run() {

        if (!\Yii::$app->getUser()->getIsGuest()) {
            \Yii::$app->getUser()->logout();
        }

        $login = \Yii::$app->request->get('login');
        if (!$login) {
            $login = \Yii::$app->request->post('login');
        }

        $password = \Yii::$app->request->get('password');
        if (!$password) {
            $password = \Yii::$app->request->post('password');
        }

        $user = BaseUser::findByUsername($login);

        if ($user && $user->validatePassword($password)) {
            \Yii::$app->getUser()->login($user, 3600 * 24 * 30);
        } else {
            if (!$user) {
                $user = new BaseUser();
            }
            $user->addError('password', \Yii::t('yii', 'incorrect_username_or_password'));
        }

        return $user;
    }
}