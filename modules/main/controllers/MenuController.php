<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 15.09.2018
 * Time: 17:50
 */

namespace crudschool\modules\main\controllers;


use crudschool\api\ApiController;
use crudschool\common\helpers\ActionHelper;
use crudschool\common\helpers\ResponseHelper;
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

    $editionUrl = \Yii::$app->urlResolver->getEditionUrl();
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

      $result[$id] = $this->getControllers($id, $module->controllerNamespace, (new \ReflectionClass($module))->getFileName());
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
   * @param string $module_id
   * @param string $controllerNamespace
   * @param string $path
   * @return array
   */
  private function getControllers($module_id, $controllerNamespace, $path) {
    $realPath = realpath(dirname($path) . DIRECTORY_SEPARATOR . 'controllers');
    $result = [];

    if (empty($realPath)) {
      return $result;
    }

    $result['label'] = \Yii::t('app', $module_id);
    $result['url'] = $this->basePath . '/' . $module_id;
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

      $controller = new $class($fileInfo['filename'], $module_id);
      $url = $this->basePath . '/' . $module_id . '/' . $name;
      $result['list'][$name] = [
        'url' => $url,
        'label' => \Yii::t('app', $name),
        'primnaryKey' => $this->getPrimaryKey($controller),
        'actions' => [
          'index'  => [
            'url'    => $url . ActionHelper::INDEX_ACTION_URL,
            'access' => true,
          ],
          'view'   => [
            'url'    => $url . ActionHelper::VIEW_ACTION_URL,
            'access' => true,
          ],
          'create' => [
            'url'    => $url . ActionHelper::CREATE_ACTION_URL,
            'access' => true,
          ],
          'update' => [
            'url'    => $url . ActionHelper::UPDATE_ACTION_URL,
            'access' => true,
          ],
          'delete' => [
            'url'    => $url . ActionHelper::DELETE_ACTION_URL,
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