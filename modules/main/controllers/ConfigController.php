<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 15.09.2018
 * Time: 18:07
 */

namespace crudschool\modules\main\controllers;


use crudschool\api\ApiController;
use crudschool\common\helpers\ActionHelper;
use crudschool\common\helpers\ResponseHelper;
use crudschool\common\helpers\UrlHelper;

class ConfigController extends ApiController {
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

        return $actions;
    }

    /**
     * @return array
     */
    public function prepareDataProvider() {
        ResponseHelper::setJSONResponseFormat();

        $result = \Yii::$app->params;

        $result['action'] = [
            ActionHelper::INDEX_ACTION_NAME  => ActionHelper::INDEX_ACTION_URL,
            ActionHelper::VIEW_ACTION_NAME   => ActionHelper::VIEW_ACTION_URL,
            ActionHelper::UPDATE_ACTION_NAME => ActionHelper::UPDATE_ACTION_URL,
            ActionHelper::CREATE_ACTION_NAME => ActionHelper::CREATE_ACTION_URL,
            ActionHelper::DELETE_ACTION_NAME => ActionHelper::DELETE_ACTION_URL,
        ];

        /* @var Request $request */
        $request = \Yii::$app->request;
        $result['edition'] = $request->getEdition();

        $result['baseUrl'] = UrlHelper::getBasePath();

        return $result;
    }
}