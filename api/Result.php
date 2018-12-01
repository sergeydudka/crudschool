<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 08.09.2018
 * Time: 10:32
 */

namespace crudschool\api;


use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class Result {
    public $list = [];
    public $total = 0;
    public $count = 0;
    public $model;

    private $result;

    public function __construct($result) {
        $this->result = $result;

        if ($result instanceof ActiveDataProvider) {
            $this->dataProviderResult($result);
        } elseif($result instanceof ActiveRecord) {
            $this->modelProviderResult($result);
        } elseif($result instanceof \Exception) {
            $this->exceptionProviderResult($result);
        } else {
            $this->arrayProviderResult($result);
        }
    }

    /**
     * @param ActiveDataProvider $result
     */
    private function dataProviderResult(ActiveDataProvider $result): void {
        $this->list = $result->getModels();
        $this->total = $result->getTotalCount();
        $this->count = $result->getCount();
        $this->model = get_class($result->models[0] ?? $result);
    }

    /**
     * @param ActiveRecord $result
     */
    private function modelProviderResult(ActiveRecord $result) {
        $this->list = $result->getAttributes();
        $this->total = 1;
        $this->count = 1;
        $this->model = get_class($result);
    }

    /**
     * @param array $result
     */
    private function arrayProviderResult($result): void {
        $result = array_filter((array)$result);
        $this->list = $result;
        $this->total = (is_array($result) || $result instanceof \Countable) ? count($result) : 0;
        $this->count = (is_array($result) || $result instanceof \Countable) ? count($result) : 0;
        $this->model = 'Array';
    }

    private function exceptionProviderResult(\Exception $result) {
        $this->list = [
            'name' => method_exists($result, 'getName') ? $result->getName() : get_class($result),
            'message' => $result->getMessage(),
            'code' => $result->getCode(),
            'status' => $result->statusCode ?? 0,
            'type' => get_class($result),
        ];
        $this->total = 1;
        $this->count = 1;
        $this->model = '';
    }

    public function getErrors() {
        if ($this->result instanceof \Exception) {
            return $this->result->getMessage();
        }
        return (is_object($this->result) && method_exists($this->result, 'getErrors')) ? $this->result->getErrors() : false;
    }
}