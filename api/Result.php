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

class Result {
    public $list = [];
    public $total = 0;
    public $count = 0;
    public $model;

    public function __construct($result) {
        if ($result instanceof ActiveDataProvider) {
            $this->dataProviderResult($result);
        } elseif($result instanceof ActiveRecord) {
            $this->modelProviderResult($result);
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
}