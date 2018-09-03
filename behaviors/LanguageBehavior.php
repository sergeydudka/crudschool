<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 02.09.2018
 * Time: 12:39
 */

namespace crudschool\behaviors;


use yii\base\ActionFilter;
use yii\base\Behavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\filters\VerbFilter;
use yii\rest\CreateAction;
use yii\rest\UpdateAction;
use yii\web\Controller;

class LanguageBehavior extends Behavior {
	const ATTRIBUTE_NAME = 'language_id';
	
	public function events() {
		return [
			ActiveRecord::EVENT_INIT => 'initFilter',
		];
	}
	
	public function initFilter() {
		parent::init();
		if ($this->owner->hasProperty(self::ATTRIBUTE_NAME)) {
			$this->owner->addWhere([self::ATTRIBUTE_NAME => \Yii::$app->get('lang')->__get(self::ATTRIBUTE_NAME)]);
		}
	}
}