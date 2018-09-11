<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 01.07.2018
 * Time: 11:02
 */

namespace crudschool\api;

use crudschool\modules\languages\helpers\SystemLanguage;
use yii\grid\SerialColumn;
use yii\rest\ActiveController;
use yii\rest\Serializer;
use yii\web\Response;


abstract class ApiController extends BaseApiController {
	public $user;
	
	/**
	 * ApiController constructor.
	 * @param string $id
	 * @param $module
	 * @param array $config
	 */
	public function __construct($id, $module, array $config = []) {
		//if (\Yii::$app->getUser()->getIsGuest() &&
		//	\Yii::$app->request->getPathInfo() !== \Yii::$app->params['loginUrl']) {
		//	$this->redirect([\Yii::$app->params['loginUrl']]);
		//}
		
		parent::__construct($id, $module, $config);
		$this->user = \Yii::$app->getUser()->getIdentity();
	}
	
	public function beforeAction($action) {
		parent::beforeAction($action);
		
		if (\Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
			\Yii::$app->response->format = Response::FORMAT_JSON;
			\Yii::$app->getResponse()->content = (new Serializer())->serialize($this->getResponse($action, []));
			//\Yii::$app->getResponse()->getHeaders()->set('Allow', 'POST GET PUT');
			\Yii::$app->end();
		}
		
		return true;
	}
	
	public function afterAction($action, $result) {
		return parent::afterAction($action, $this->getResponse($action, $result));
	}
	
	private function getResponse($action, $result) : ApiResult {
		$response = new ApiResult($action, $result);
		if ($this->modelClass) {
			$response->setModel(new $this->modelClass());
		}
		//\Yii::$app->response->format = Response::FORMAT_JSON;
		return $response;
	}
	
	//public function checkAccess($action, $model = null, $params = []) {
	//	parent::checkAccess($action, $model, $params);
	//}
}