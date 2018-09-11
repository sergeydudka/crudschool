<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 08.09.2018
 * Time: 10:32
 */

namespace crudschool\api;


use yii\data\ActiveDataProvider;

class Result {
	public $list = [];
	public $total = 0;
	public $count = 0;
	public $model;
	
	public function __construct($result) {
		if ($result instanceof ActiveDataProvider) {
			$this->dataProviderResult($result);
		} else {
			$this->arrayProviderResult($result);
		}
	}
	
	private function dataProviderResult($result) {
		$this->list = $result->getModels();
		$this->total = $result->getTotalCount();
		$this->count = $result->getCount();
		$this->model = $result->query->modelClass;
	}
	
	private function arrayProviderResult($result) {
		$this->list = $result;
		$this->total = (is_array($result) || $result instanceof \Countable) ? count($result) : 1;
		$this->count = (is_array($result) || $result instanceof \Countable) ? count($result) : 1;
		$this->model = 'Array';
	}
	
	
}