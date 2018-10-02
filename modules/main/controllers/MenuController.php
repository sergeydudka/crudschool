<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 15.09.2018
 * Time: 17:50
 */

namespace crudschool\modules\main\controllers;


use crudschool\api\ApiController;
use crudschool\common\helpers\AccessHelper;
use crudschool\common\helpers\ActionHelper;
use crudschool\common\helpers\ResponseHelper;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\web\Controller;

class MenuController extends ApiController {
    /**
     * @var string
     */
    public $modelClass = '';
    /**
     * @var string
     */
    private $basePath = '';

    /**
     * @var array
     */
    const IGNORE_CONTROLLERS = [
        'default',
    ];
    /**
     * @var array
     */
    const IGNORE_MODULES = [
        'debug',
        'gii',
    ];

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
     * @throws \ReflectionException
     */
    public function prepareDataProvider() {
        ResponseHelper::setJSONResponseFormat();

        $url = \yii\helpers\Url::base(true);

        $editionUrl = \Yii::$app->request->getEditionUrl();
        if ($editionUrl) {
            $url = trim(strtr($url, ["/$editionUrl" => '/']), '/');
        }
        $this->basePath = $url;

        $result = [];
        foreach (\Yii::$app->getModules() as $id => $module) {
            if ($id == $this->module->id || in_array($id, self::IGNORE_MODULES)) {
                continue;
            }
            $module = $this->createClass($module);
            $module->id = $id;
            $result[$id] = $this->getControllers($module, $module->controllerNamespace, (new \ReflectionClass($module))->getFileName());
        }
        return $result;
    }

    /**
     * @param string|object $module
     * @return object
     */
    private function createClass($module) {
        return is_object($module) ? $module : new $module['class']([]);
    }

    /**
     * @param        $module_id
     * @param string $controllerNamespace
     * @param string $path
     * @return array
     */
    private function getControllers($module, $controllerNamespace, $path) {
        $realPath = realpath(dirname($path) . DIRECTORY_SEPARATOR . 'controllers');
        $result = [];

        if (empty($realPath)) {
            return $result;
        }

        $result['label'] = \Yii::t('app', $module->id);
        $result['url'] = $this->basePath . '/' . $module->id;
        $result['default'] = $result['url'] . '/' . $module->defaultRoute;
        $result['defaultRoute'] = $module->defaultRoute;
        $result['list'] = [];

        foreach (scandir($realPath) as $fileName) {
            if ($fileName == '.' || $fileName == '..') {
                continue;
            }

            $fileInfo = pathinfo($fileName);

            $name = Inflector::camel2id(str_replace('Controller', '', $fileInfo['filename']));

            if (in_array($name, self::IGNORE_CONTROLLERS)) {
                continue;
            }

            $class = $controllerNamespace . '\\' . $fileInfo['filename'];

            $controller = new $class($fileInfo['filename'], $module->id);
            $url = $this->basePath . '/' . $module->id . '/' . $name;
            $result['list'][$name] = [
                'url'        => $url,
                'label'      => \Yii::t('app', $name),
                'primaryKey' => $this->getPrimaryKey($controller),
                'model'      => $controller->modelClass,
                'actions'    => [
                    'index'  => [
                        'url'    => $url . ActionHelper::INDEX_ACTION_URL,
                        //'access' => AccessHelper::getActionAccess($controller->modelClass,
                        //  ActionHelper::INDEX_ACTION_URL),
                        'access' => true,
                    ],
                    'view'   => [
                        'url'    => $url . ActionHelper::VIEW_ACTION_URL,
                        /*'access' => AccessHelper::getActionAccess($controller->modelClass, ActionHelper::VIEW_ACTION_URL),*/
                        'access' => true,
                    ],
                    'create' => [
                        'url'    => $url . ActionHelper::CREATE_ACTION_URL,
                        /*'access' => AccessHelper::getActionAccess($controller->modelClass, ActionHelper::CREATE_ACTION_URL),*/
                        'access' => true,
                    ],
                    'update' => [
                        'url'    => $url . ActionHelper::UPDATE_ACTION_URL,
                        /*'access' => AccessHelper::getActionAccess($controller->modelClass, ActionHelper::UPDATE_ACTION_URL),*/
                        'access' => true,
                    ],
                    'delete' => [
                        'url'    => $url . ActionHelper::DELETE_ACTION_URL,
                        /*'access' => AccessHelper::getActionAccess($controller->modelClass, ActionHelper::DELETE_ACTION_URL),*/
                        'access' => true,
                    ],
                ],
            ];
        }
        return $result;
    }

    /**
     * @param Controller $controller
     * @return string
     */
    private function getPrimaryKey(Controller $controller) {

        if (!$controller->modelClass) {
            return '';
        }

        /* @var ActiveRecord $model */
        $model = new $controller->modelClass();
        return (string)key($model->getPrimaryKey(true));
    }
}