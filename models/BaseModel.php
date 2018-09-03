<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 02.09.2018
 * Time: 12:14
 */

namespace crudschool\models;


use crudschool\interfaces\AngularViewInterface;
use yii\db\ActiveRecord;
use yii\rest\CreateAction;
use yii\rest\IndexAction;
use yii\rest\UpdateAction;

class BaseModel extends ActiveRecord implements AngularViewInterface {
	protected $hiddenFields = [];
	protected $fields;
	
	public function init() {
		parent::init();
		/* ActionIndex hiddenFields*/
		$this->setHiddenFields(IndexAction::class, 'description');
		
		/* CreateAction hiddenFields*/
		$this->setHiddenFields(CreateAction::class, 'created_by');
		$this->setHiddenFields(CreateAction::class, 'updated_by');
		$this->setHiddenFields(CreateAction::class, 'language_id');
		$this->setHiddenFields(CreateAction::class, 'created_at');
		$this->setHiddenFields(CreateAction::class, 'updated_at');
		
		/* UpdateAction hiddenFields*/
		$this->setHiddenFields(UpdateAction::class, 'created_by');
		$this->setHiddenFields(UpdateAction::class, 'updated_by');
		$this->setHiddenFields(UpdateAction::class, 'language_id');
		$this->setHiddenFields(UpdateAction::class, 'created_at');
		$this->setHiddenFields(UpdateAction::class, 'updated_at');
	}
	
	public function fields() {
		$actionName = get_class(\Yii::$app->controller->action);
		if ($this->hasHiddenFields($actionName)) {
			return array_diff(parent::fields(), $this->getHiddenFields($actionName));
		}
		return parent::fields();
	}
	
	public function getHiddenFields($actionName) {
		return $this->hiddenFields[$actionName] ?? [];
	}
	
	public function setHiddenFields($actionName, $fieldName) {
		if (!isset($this->hiddenFields[$actionName])) {
			$this->hiddenFields[$actionName] = [];
		}
		$this->hiddenFields[$actionName][] = $fieldName;
	}
	
	public function hasHiddenFields($actionName) {
		return !empty($this->hiddenFields[$actionName]);
	}
}