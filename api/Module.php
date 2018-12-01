<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 25.11.2018
 * Time: 17:27
 */

namespace crudschool\api;


use crudschool\common\helpers\AccessHelper;
use yii\web\ForbiddenHttpException;

class Module extends \yii\base\Module {

    public function beforeAction($action) {
        $this->checkAccess($action->id);
        return parent::beforeAction($action);
    }

    private function checkAccess($action) {
        $modelClass = \Yii::$app->controller->modelClass;
        if ($modelClass && !AccessHelper::getActionAccess($modelClass, $action)) {
            throw new ForbiddenHttpException(\Yii::t('app', 'access_denied_for_action'));
        }
    }

}