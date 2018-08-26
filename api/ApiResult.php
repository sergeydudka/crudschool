<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 15.07.2018
 * Time: 18:34
 */

namespace crudschool\api;


use yii\base\BaseObject;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class ApiResult extends BaseObject {
	public $result;
	public $fields;
	public $labels;
	public $rules;
	public $scenarios;
	public $errors;
	public $status;
	
	
	/**
	 * ApiResult constructor.
	 * @param ActiveDataProvider|array $result
	 */
	public function __construct($result) {
		parent::__construct();
		
		if ($result instanceof ActiveDataProvider) {
			$this->result = [
				'list' => $result->getModels(),
				'total' => $result->getTotalCount(),
				'count' => $result->getCount(),
				'behaviours' => $result->getBehaviors(),
			];
			$this->errors = $result->getErrors();
		} else {
			$this->result = [
				'list' => $result,
				'total' => (is_array($result) || $result instanceof \Countable) ? count($result) : 1,
				'count' => (is_array($result) || $result instanceof \Countable) ? count($result) : 1,
				'behaviours' => [],
			];
			
			$this->errors = (is_object($result) && method_exists($result, 'getErrors')) ? $result->getErrors() : false;
		}
	}
	
	public function setModel(ActiveRecord $model) {
		$this->status = !$this->errors;
		$this->fields = $model->getTableSchema();
		$this->rules = $model->rules();
		$this->labels = $model->attributeLabels();
		$this->scenarios = $model->scenarios();
		$this->errors = $this->errors;
	}
}