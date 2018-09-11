<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 05.08.2018
 * Time: 11:47
 */

namespace crudschool\api;

use crudschool\interfaces\AngularModelInterface;
use crudschool\modules\languages\helpers\SystemLanguage;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\Controller;
use \Yii;

class BaseApiController extends ActiveController {
	private $edition;
	public $dataFilter;
	
	public function init() {
		parent::init();
		$this->edition = \Yii::$app->edition->getInstance();
	}
	
	public function getEdition() {
		return $this->edition;
	}
	
	public function actions() {
		$actions = parent::actions();
		
		$actions['index']['prepareDataProvider'] = [$this, 'indexDataProvider'];
		
		return $actions;
	}
	
	public function indexDataProvider($action, $filter) {
		$requestParams = Yii::$app->getRequest()->getBodyParams();
		if (empty($requestParams)) {
			$requestParams = Yii::$app->getRequest()->getQueryParams();
		}
		
		$filter = null;
		if ($this->dataFilter !== null) {
			$this->dataFilter = Yii::createObject($this->dataFilter);
			if ($this->dataFilter->load($requestParams)) {
				$filter = $this->dataFilter->build();
				if ($filter === false) {
					return $this->dataFilter;
				}
			}
		}
		
		/* @var $modelClass \yii\db\BaseActiveRecord */
		$modelClass = $this->modelClass;
		
		$select = $this->getSelect($action);
		
		$query = $modelClass::find();
		
		if (!empty($filter)) {
			$query->andWhere($filter);
		}
		
		if (!empty($select)) {
			$query->select($select);
		}
		
		return Yii::createObject([
			'class' => ActiveDataProvider::className(),
			'query' => $query,
			'pagination' => [
				'params' => $requestParams,
			],
			'sort' => [
				'params' => $requestParams,
			],
		]);
	}
	
	private function getSelect($action) {
		$model = new $this->modelClass;
		if ($model instanceof AngularModelInterface) {
			$displayFields = $model->getDisplayFields();
			return $displayFields[get_class($action)] ?? false;
		}
		return false;
	}
}