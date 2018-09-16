<?php

namespace crudschool\api;

use crudschool\interfaces\AngularModelInterface;
use frontend\models\ResetPasswordForm;
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
  public $errors = FALSE;

  private $action;

  /**
   * ApiResult constructor.
   * @param ActiveDataProvider|array $result
   * @param Action                   $action
   */
  public function __construct($action, $result) {
    parent::__construct();
    $this->action = $action;
    $this->result = new Result($result);
    $this->errors = (is_object($result) && method_exists($result, 'getErrors')) ? $result->getErrors() : FALSE;
    $this->status = !$this->errors;
  }

  /**
   * @param ActiveRecord $model
   */
  public function setModel(ActiveRecord $model): void {
    $fieldCollection = new FieldCollection($model);
    $this->fields = $fieldCollection->getFields();
    $this->errors = $model->getErrors();
    $this->status = !$this->errors;
  }

  /**
   * @return string
   */
  public function __toString(): string {
    return json_encode($this); // TODO: убрать костыль
  }
}