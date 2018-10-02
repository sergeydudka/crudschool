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
			    if ($relationship->getType() === RelationshipField::HAS_MANY_REL) {
                    $this->$attribute = $this->hasMany($relationship->getModel(),
                        [
                            $relationship->getField() => $attribute
                        ]
                    )->asArray()->all();
                } else {
                    $this->$attribute = $this->hasOne($relationship->getModel(),
                        [
                            $relationship->getField() => $attribute
                        ]
                    )->limit(1)->asArray()->one();
                }
			} else {
				throw new InvalidConfigException('Getting unknown property: ' . $class . '::' . $attribute . '.');
			}
		}
	}
}