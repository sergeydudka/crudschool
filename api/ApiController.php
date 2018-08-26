<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 01.07.2018
 * Time: 11:02
 */

namespace crudschool\api;

use crudschool\modules\languages\helpers\SystemLanguage;
use yii\rest\ActiveController;
use yii\web\Response;


abstract class ApiController extends BaseApiController {
	public $user;
	
	/**
	 * ApiController constructor.
	 * @param string $id
	 * @param $module
	 * @param array $config
	 */
	public function __construct(string $id, $module, array $config = []) {
		//if (\Yii::$app->getUser()->getIsGuest() &&
		//	\Yii::$app->request->getPathInfo() !== \Yii::$app->params['loginUrl']) {
		//	$this->redirect([\Yii::$app->params['loginUrl']]);
		//}
		
		parent::__construct($id, $module, $config);
		$this->user = \Yii::$app->getUser()->getIdentity();
	}
	
	public function afterAction($action, $result) {
		$response = new ApiResult($result);
		if ($this->modelClass) {
			$response->setModel(new $this->modelClass());
		}
		\Yii::$app->response->format = Response::FORMAT_JSON;
		return parent::afterAction($action, $response);
	}
	
	//public function checkAccess($action, $model = null, $params = []) {
	//	parent::checkAccess($action, $model, $params);
	//}
}