<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 22.07.2018
 * Time: 13:14
 */

namespace crudschool\models;


use crudschool\interfaces\RelationshipInteface;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\rest\ActiveController;

abstract class RelationshipModel extends BaseModel implements RelationshipInteface {
	
	public function afterFind() {
		parent::afterFind();
		
		if (\Yii::$app->id != 'backend' || !(\Yii::$app->controller instanceof ActiveController)) {
			return;
		}
		
		$class = get_called_class();
		
		if ($class != \Yii::$app->controller->modelClass) {
			return;
		}
		
		/* @var $class RelationshipInteface*/
		
		foreach ($class::relationships() as $attribute => $relationship) {
			if ($relationship instanceof RelationshipField) {
				$this->$attribute = $this->{$relationship->getMethod()};
			} else if ($this->hasAttribute($attribute)) {
				$this->$attribute = $this->$relationship;
			} else {
				throw new InvalidConfigException('Getting unknown property: ' . $class . '::' . $attribute . '.');
			}
		}
	}
}