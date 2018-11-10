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
    public $errors = false;

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
        $this->errors = (is_object($result) && method_exists($result, 'getErrors')) ? $result->getErrors() : false;
        $this->status = !$this->errors;
    }

    /**
     * @param ActiveRecord $model
     */
    public function setModel(ActiveRecord $model) {
        $fieldsCollection = new FieldCollection($model);
        $this->fields = $fieldsCollection->fields;
        $this->errors = $model->getErrors();
        $this->status = !$this->errors;
    }

    /**
     * @return string
     */
    public function __toString() {
        return json_encode($this); // TODO: убрать костыль
    }
}