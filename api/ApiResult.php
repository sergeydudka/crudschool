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
use yii\rest\CreateAction;
use yii\rest\DeleteAction;
use yii\rest\UpdateAction;

class ApiResult extends BaseObject {
	public $status;
	public $statusCode = 200;
	public $fields = [];
	public $result;
	public $errors = false;
	
	private $action;
	
	/**
	 * ApiResult constructor.
	 * @param ActiveDataProvider|array $result
	 * @param Action $action
	 */
	public function __construct($action, $result) {
		parent::__construct();
		$this->action = $action;
		$this->result = new Result($result);
		$this->errors = (is_object($result) && method_exists($result, 'getErrors')) ? $result->getErrors() : false;
		$this->status = !$this->errors;
	}
	
	public function setModel(ActiveRecord $model) {
		$fieldCollection = new FieldCollection($model);
		$this->fields = $fieldCollection->getFields();
		$this->errors = $model->getErrors();
		$this->status = !$this->errors;
	}
	
	public function __toString() {
		return json_encode($this); // TODO: убрать костыль
	}
}