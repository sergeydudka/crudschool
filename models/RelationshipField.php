<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 01.09.2018
 * Time: 19:06
 */

namespace crudschool\models;


use yii\base\BaseObject;

class RelationshipField extends BaseObject {
	public $field;
	public $label;
	public $model;
	public $method;
	public $params;
	
	public function __construct($config = []) {
		parent::__construct($config);
	}
	
	
	public function getField() {
		return $this->field;
	}
	
	public function getLabel() {
		return $this->label;
	}
	
	public function getModel() {
		return $this->model;
	}
	
	public function getMethod() {
		return $this->method;
	}
	
	public function getParams() {
		return $this->params ? $this->params : [];
	}
}