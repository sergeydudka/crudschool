<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 15.07.2018
 * Time: 18:34
 */

namespace crudschool\api;


use crudschool\interfaces\AngularModelInterface;
use crudschool\models\RelationshipModel;
use yii\base\Action;
use yii\base\BaseObject;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class ApiResult extends BaseObject {
	public $status;
	public $statusCode;
	public $fields;
	public $result;
	public $errors;
	
	private $action;
	
	/**
	 * ApiResult constructor.
	 * @param ActiveDataProvider|array $result
	 * @param Action $action
	 */
	public function __construct($action, $result) {
		parent::__construct();
		$this->action = $action;
		if ($result instanceof ActiveDataProvider) {
			$this->result = [
				'list' => $result->getModels(),
				'total' => $result->getTotalCount(),
				'count' => $result->getCount(),
				'model' => $result->getModels()[0]->className(),
			];
			$this->errors = false;
		} else {
			$this->result = [
				'list' => $result,
				'total' => (is_array($result) || $result instanceof \Countable) ? count($result) : 1,
				'count' => (is_array($result) || $result instanceof \Countable) ? count($result) : 1,
				'model' => 'Array',
			];
			
			$this->errors = (is_object($result) && method_exists($result, 'getErrors')) ? $result->getErrors() : false;
			
			$this->status = !$this->errors;
			$this->statusCode = 200;
			$this->fields = [];
		}
	}
	
	public function setModel(ActiveRecord $model) {
		$this->status = !$this->errors;
		$this->statusCode = 200;
		$this->fields = $this->getModelFields($model);
		$this->errors = $this->errors;
	}
	
	private function getModelFields(ActiveRecord $model) {
		$schema = $model->getTableSchema();
		$relationships = ($model instanceof RelationshipModel) ? $model->relationships() : [];
		$labels = $model->attributeLabels();
		$displayFields = $model->fields();
		
		$result = [];
		
		foreach ($schema->columns as $key => $column) {
			/** var yii\db\mysql\ColumnSchema $column */
			$field = [];
			$field['rel'] = $relationships[$key] ?? false;
			$field['label'] = $labels[$key] ?? false;
			
			if ($field['rel']) {
				$rel = $field['rel']->getModel();
				if (is_callable([$rel, 'getDropdown'])) {
					$field['enumValues'] = call_user_func_array([$rel, 'getDropdown'], $field['rel']->getParams());
				}
			}
			
			$field['display'] = in_array($key, $displayFields);
			$field['name'] = $column->name;
			$field['allowNull'] = $column->allowNull;
			$field['type'] = $column->type;
			$field['defaultValue'] = $column->defaultValue;
			$field['size'] = $column->size;
			$field['precision'] = $column->precision;
			$field['isPrimaryKey'] = $column->isPrimaryKey;
			$field['comment'] = $column->comment;
			$result[$key] = $column;
		}
		
		return $result;
	}
}