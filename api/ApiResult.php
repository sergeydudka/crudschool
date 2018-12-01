<?php

namespace crudschool\api;

use crudschool\interfaces\AngularModelInterface;
use frontend\models\ResetPasswordForm;
use yii\base\Action;
use yii\base\BaseObject;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\rest\ActiveController;
use yii\rest\Controller;
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
     * @param ActiveController         $controller
     */
    public function __construct($controller, $result) {
        parent::__construct();
        $this->action = $controller->action;
        $this->result = new Result($result);
        $this->errors = $this->result->getErrors();
        $this->status = !$this->errors;
        $this->statusCode = $result->status ?? 200;
        if ($controller->modelClass) {
            $this->setModel(\Yii::createObject($controller->modelClass));
        }
    }

    /**
     * @param ActiveRecord $model
     */
    private function setModel(ActiveRecord $model) {
        $fieldsCollection = new FieldCollection($model);
        $this->fields = $fieldsCollection->fields;
        $this->errors = $model->getErrors();
        $this->status = !$this->errors;
    }
}