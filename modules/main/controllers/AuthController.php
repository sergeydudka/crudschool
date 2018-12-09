<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 15.09.2018
 * Time: 18:07
 */

namespace crudschool\modules\main\controllers;


use crudschool\api\actions\LoginAction;
use crudschool\api\actions\LogoutAction;
use crudschool\api\ApiController;
use crudschool\common\helpers\ActionHelper;
use crudschool\common\helpers\ResponseHelper;
use crudschool\models\BaseUser;

class AuthController extends ApiController {
    /**
     * @var string
     */
    public $modelClass = '';

    /**
     * @return array
     */
    public function actions() {
        $actions = parent::actions();
        unset($actions['delete'], $actions['create'], $actions['update']);

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        $actions['login'] = [
            'class' => LoginAction::class,
            'modelClass' => $this->modelClass,
        ];
        $actions['logout'] = [
            'class' => LogoutAction::class,
            'modelClass' => $this->modelClass,
        ];

        return $actions;
    }

    public function afterAction($action, $result) {
        ResponseHelper::setJSONResponseFormat();
        return parent::afterAction($action, $result);
    }

    /**
     * @return null|\yii\web\IdentityInterface
     * @throws \Throwable
     */
    public function prepareDataProvider() {
        return \Yii::$app->getUser()->getIdentity();
    }

    public function logoutUser() {
        if (!\Yii::$app->getUser()->getIsGuest()) {
            return \Yii::$app->getUser()->logout();
        }

        return false;
    }

    /**
     * @return array
     */
    protected function verbs() {
        return [
            'index' => ['GET', 'HEAD', 'OPTIONS'],
            'login' => ['GET', 'POST', 'PUT', 'PATCH', 'OPTIONS'],
            'logout' => ['POST', 'GET', 'OPTIONS'],
        ];
    }
}