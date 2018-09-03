<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 28.07.2018
 * Time: 14:44
 */

namespace crudschool\behaviors;


use crudschool\interfaces\AngularViewInterface;
use crudschool\modules\users\models\User;
use yii\db\ActiveRecord;
use yii\rest\CreateAction;
use yii\rest\IndexAction;
use yii\rest\UpdateAction;

class BlameableBehavior extends \yii\behaviors\BlameableBehavior {
	
	public function events() {
		return [
			ActiveRecord::EVENT_BEFORE_INSERT => 'setCreater',
			ActiveRecord::EVENT_BEFORE_UPDATE => 'setUpdater',
			ActiveRecord::EVENT_BEFORE_VALIDATE => 'setCreater',
		];
	}
	
	public function setCreater() {
		if (!$this->owner->created_by) {
			$this->owner->created_by = \Yii::$app->getUser()->getId() ?? 1;
		}
		$this->setUpdater();
	}
	
	public function setUpdater() {
		$this->owner->updated_by = \Yii::$app->getUser()->getId() ?? 1;
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreated() {
		/* @var $owner ActiveRecord */
		$owner = $this->owner;
		return $owner->hasOne(User::class, ['user_id' => 'created_by']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUpdated() {
		/* @var $owner ActiveRecord */
		$owner = $this->owner;
		return $owner->hasOne(User::class, ['user_id' => 'updated_by']);
	}
}