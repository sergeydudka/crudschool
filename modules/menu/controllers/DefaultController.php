<?php

namespace crudschool\modules\menu\controllers;

use crudschool\api\ApiController;

/**
 * Default controller for the `menu` module
 */
class DefaultController extends ApiController {
	
	
	public $modelClass = '';
	
	const IGNORE_CONTROLLERS = [
		'default'
	];
	
	const INDEX_ACTION_URL = "";
	const VIEW_ACTION_URL = "/view";
	const UPDATE_ACTION_URL = "/update";
	const CREATE_ACTION_URL = "/create";
	const DELETE_ACTION_URL = "/delete";
	
	public function actions() {
		
		$actions = parent::actions();
		unset($actions['delete'], $actions['create'], $actions['update']);
		
		$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
		
		return $actions;
		
	}
	
	public function prepareDataProvider() {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$result = [];
		foreach (\Yii::$app->getModules() as $id => $module) {
			if ($id == $this->module->id || $id == 'debug' || $id == 'gii') {
				continue;
			}
			$module = $this->createClass($module);
			$class = new \ReflectionClass($module);
			
			$result[$id] = $this->getControllers($id, $module->controllerNamespace,  $class->getFileName());
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
	
	private function getControllers($module_id, $controllerNamespace, $path) {
		$realPath = realpath(dirname($path) . DIRECTORY_SEPARATOR . 'controllers');
		$result = [];
		
		if (empty($realPath)) {
			return $result;
		}
		
		foreach (scandir($realPath) as $fileName) {
			if ($fileName == '.' || $fileName == '..') {
				continue;
			}
			
			$fileInfo = pathinfo($fileName);
			
			$name = \yii\helpers\Inflector::camel2id(str_replace('Controller', '', $fileInfo['filename']));
			
			if (in_array($name, self::IGNORE_CONTROLLERS)) {
				continue;
			}
			
			$class = $controllerNamespace . '\\' . $fileInfo['filename'];
			
			$controller = new $class($fileInfo['filename'], $module_id);
			$url = '/' . $module_id . '/' . $name;
			$result[$name] = [
				'class' => $class,
				'url' => $url,
				'label' => '',
				'actions' => [
					'index' => [
						'url' => $url . self::INDEX_ACTION_URL,
						'access' => true,
					],
					'view' => [
						'url' => $url . self::VIEW_ACTION_URL,
						'access' => true,
					],
					'create' => [
						'url' => $url . self::CREATE_ACTION_URL,
						'access' => true,
					],
					'update' => [
						'url' => $url . self::UPDATE_ACTION_URL,
						'access' => true,
					],
					'delete' => [
						'url' => $url . self::DELETE_ACTION_URL,
						'access' => true,
					],
				],
			];
		}
		return $result;
	}
}
