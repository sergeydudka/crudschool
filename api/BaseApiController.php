<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 05.08.2018
 * Time: 11:47
 */

namespace crudschool\api;

use crudschool\modules\languages\helpers\SystemLanguage;
use yii\rest\ActiveController;
use yii\web\Controller;

class BaseApiController extends ActiveController {
	private $lang;
	
	public function init() {
		parent::init();
		
		$this->lang = \Yii::$app->lang::getInstance();
	}
	
	public function getLang() {
		return $this->lang;
	}
}